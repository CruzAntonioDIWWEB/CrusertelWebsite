<?php
namespace Controllers;

class ContactController extends BaseController {
    
    public function showContactForm() {
        $data = [
            'title' => 'Contacto - Crusertel',
            'success' => $this->getGetData('success'),
            'error' => $this->getGetData('error')
        ];
        
        $this->loadView('contact/index', $data);
    }
    
    public function submitContactForm() {
        if (!$this->isPost()) {
            error_log("Contact form: Invalid request method");
            $this->redirect('index.php?page=contact&error=invalid_method');
            return;
        }
        
        // Get and validate form data
        $name = trim($this->getPostData('nombre') ?? '');
        $email = trim($this->getPostData('email') ?? '');
        $phone = trim($this->getPostData('telefono') ?? '');
        $subject = trim($this->getPostData('asunto') ?? '');
        $message = trim($this->getPostData('mensaje') ?? '');
        
        // Enhanced logging for debugging empty emails
        error_log("=== CONTACT FORM SUBMISSION ===");
        error_log("Raw POST data: " . print_r($_POST, true));
        error_log("Processed data:");
        error_log("- Name: " . ($name ? "'{$name}' (" . strlen($name) . " chars)" : 'EMPTY'));
        error_log("- Email: " . ($email ? "'{$email}' (" . strlen($email) . " chars)" : 'EMPTY'));
        error_log("- Phone: " . ($phone ? "'{$phone}' (" . strlen($phone) . " chars)" : 'EMPTY'));
        error_log("- Subject: " . ($subject ? "'{$subject}' (" . strlen($subject) . " chars)" : 'EMPTY'));
        error_log("- Message: " . ($message ? 'PROVIDED (' . strlen($message) . ' chars)' : 'EMPTY'));
        error_log("- User IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));
        error_log("- User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'));
        
        // Basic validation
        $errors = $this->validateContactForm($name, $email, $phone, $subject, $message);
        
        if (!empty($errors)) {
            error_log("Contact form validation failed: " . implode(', ', $errors));
            $this->redirect('index.php?page=contact&error=' . urlencode(implode(', ', $errors)));
            return;
        }
        
        error_log("Contact form validation passed - proceeding to save");
        
        // Prepare data for processing
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];
        
        // Save to database first
        try {
            $contact = new \Models\Contact(); // Fix: Use full namespace
            $databaseSaved = $contact->save($name, $email, $phone, $subject, $message);
            
            if (!$databaseSaved) {
                error_log("CRITICAL: Failed to save contact form to database");
                $this->redirect('index.php?page=contact&error=save_failed');
                return;
            }
            
            error_log("Contact form saved to database successfully");
            
        } catch (Exception $e) {
            error_log("CRITICAL: Database exception: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->redirect('index.php?page=contact&error=save_failed');
            return;
        }
        
        // Initialize email service
        try {
            $emailService = new \Services\EmailService(); // Fix: Use full namespace
            
            // Send main contact email
            $emailSent = $emailService->sendContactFormEmail($contactData);
            
            if ($emailSent) {
                error_log("SUCCESS: Contact email sent via IONOS SMTP");
                
                // Send confirmation email to user (optional, but nice touch)
                $confirmationSent = $emailService->sendConfirmationEmail($contactData);
                if ($confirmationSent) {
                    error_log("SUCCESS: Confirmation email sent to user");
                } else {
                    error_log("WARNING: Main email sent but confirmation to user failed");
                }
                
            } else {
                error_log("ERROR: Main contact email failed to send");
                
                // Create emergency backup
                $emailService->sendEmergencyLog($contactData, 'Main email send failed');
                
                // Still redirect to success since data is saved in database
                // You can manually check the emergency log
            }
            
        } catch (Exception $e) {
            error_log("CRITICAL: EmailService exception: " . $e->getMessage());
            
            // Create manual emergency log
            $this->createManualBackup($contactData, $e->getMessage());
        }
        
        // Always redirect to success if data was saved to database
        // The important thing is that we have the contact info saved
        $this->redirect('index.php?page=contact&success=1');
    }
    
    private function validateContactForm($name, $email, $phone, $subject, $message) {
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio';
        } elseif (strlen($name) < 2) {
            $errors[] = 'El nombre debe tener al menos 2 caracteres';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El formato del email no es válido';
        }
        
        if (empty($subject)) {
            $errors[] = 'El asunto es obligatorio';
        } elseif (strlen($subject) < 3) {
            $errors[] = 'El asunto debe tener al menos 3 caracteres';
        }
        
        if (empty($message)) {
            $errors[] = 'El mensaje es obligatorio';
        } elseif (strlen($message) < 10) {
            $errors[] = 'El mensaje debe tener al menos 10 caracteres';
        }
        
        // Phone is optional but validate if provided
        if (!empty($phone) && strlen($phone) < 6) {
            $errors[] = 'El teléfono debe tener al menos 6 dígitos';
        }
        
        return $errors;
    }
    
    private function createManualBackup($contactData, $error) {
        $logFile = __DIR__ . '/../logs/contact_manual_backup.log';
        $logDir = dirname($logFile);
        
        // Ensure logs directory exists
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "\n=== MANUAL CONTACT BACKUP - $timestamp ===\n";
        $logEntry .= "ERROR: $error\n";
        $logEntry .= "Name: {$contactData['name']}\n";
        $logEntry .= "Email: {$contactData['email']}\n";
        $logEntry .= "Phone: {$contactData['phone']}\n";
        $logEntry .= "Subject: {$contactData['subject']}\n";
        $logEntry .= "Message: {$contactData['message']}\n";
        $logEntry .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
        $logEntry .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n";
        $logEntry .= "=== END MANUAL BACKUP ===\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        error_log("Manual contact backup created: $logFile");
    }
}