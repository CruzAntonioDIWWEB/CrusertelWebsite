<?php
namespace Controllers;

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Contact.php';

use BaseController\BaseController;
use Models\Contact;

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
            $this->redirect('index.php?page=contact&error=invalid_method');
            return;
        }
        
        // Get and validate form data
        $name = trim($this->getPostData('nombre') ?? '');
        $email = trim($this->getPostData('email') ?? '');
        $phone = trim($this->getPostData('telefono') ?? '');
        $subject = trim($this->getPostData('asunto') ?? '');
        $message = trim($this->getPostData('mensaje') ?? '');
        
        // Basic validation
        $errors = $this->validateContactForm($name, $email, $phone, $subject, $message);
        
        if (!empty($errors)) {
            $this->redirect('index.php?page=contact&error=' . urlencode(implode(', ', $errors)));
            return;
        }
        
        // Save to database
        $contact = new Contact();
        $result = $contact->save($name, $email, $phone, $subject, $message);
        
        if ($result) {
            // Send email notification
            $this->sendEmailNotification($name, $email, $phone, $subject, $message);
            $this->redirect('index.php?page=contact&success=1');
        } else {
            $this->redirect('index.php?page=contact&error=save_failed');
        }
    }
    
    private function validateContactForm($name, $email, $phone, $subject, $message) {
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        
        if (empty($subject)) {
            $errors[] = 'El asunto es obligatorio';
        }
        
        if (empty($message)) {
            $errors[] = 'El mensaje es obligatorio';
        }
        
        return $errors;
    }
    
    private function sendEmailNotification($name, $email, $phone, $subject, $message) {
    // Validate all inputs are not empty
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        error_log("Contact form: Empty required fields detected - Name: " . ($name ? 'OK' : 'EMPTY') . 
                 ", Email: " . ($email ? 'OK' : 'EMPTY') . 
                 ", Subject: " . ($subject ? 'OK' : 'EMPTY') . 
                 ", Message: " . ($message ? 'OK' : 'EMPTY'));
        return false;
    }
    
    $to = "info@crusertel.es";
    $emailSubject = "Nuevo mensaje del formulario de contacto - " . $subject;
    
    // Create more robust email body with clear separators
    $body = "=== NUEVO MENSAJE DEL FORMULARIO DE CONTACTO ===\n\n";
    $body .= "Nombre: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Teléfono: " . ($phone ?: 'No proporcionado') . "\n";
    $body .= "Asunto: " . $subject . "\n";
    $body .= "Fecha: " . date('d/m/Y H:i:s') . "\n\n";
    $body .= "=== MENSAJE ===\n";
    $body .= $message . "\n\n";
    $body .= "=== INFORMACIÓN TÉCNICA ===\n";
    $body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Desconocida') . "\n";
    $body .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido') . "\n";
    $body .= "Sitio web: " . ($_SERVER['HTTP_HOST'] ?? 'crusertel.es') . "\n";
    
    // Enhanced headers
    $headers = [
        'From: "Formulario Crusertel" <noreply@crusertel.es>',
        'Reply-To: "' . $name . '" <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: Crusertel Contact Form v1.0',
        'X-Priority: 2'
    ];
    
    // Log the email attempt with full details
    error_log("Attempting to send contact email - Subject: $subject, From: $name <$email>, Body length: " . strlen($body));
    
    // Send the email
    $result = mail($to, $emailSubject, $body, implode("\r\n", $headers));
    
    if ($result) {
        error_log("Contact email sent successfully to $to");
    } else {
        error_log("CRITICAL: Failed to send contact email to $to. Check mail server configuration.");
        error_log("Email details - Subject: $emailSubject, Body: " . substr($body, 0, 200) . "...");
    }
    
    return $result;
}

private function sendBackupNotification($name, $email, $phone, $subject, $message) {
    // Create a simple file log as backup
    $logFile = __DIR__ . '/../logs/contact_backup.log';
    $logDir = dirname($logFile);
    
    // Ensure logs directory exists
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "\n=== CONTACT FORM SUBMISSION - $timestamp ===\n";
    $logEntry .= "Name: $name\n";
    $logEntry .= "Email: $email\n";
    $logEntry .= "Phone: " . ($phone ?: 'Not provided') . "\n";
    $logEntry .= "Subject: $subject\n";
    $logEntry .= "Message: $message\n";
    $logEntry .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
    $logEntry .= "=== END ENTRY ===\n";
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    error_log("Contact form backup logged to: $logFile");
}

// Update your submitContactForm method to use both:
public function submitContactForm() {
    if (!$this->isPost()) {
        $this->redirect('index.php?page=contact&error=invalid_method');
        return;
    }
    
    // Get and validate form data
    $name = trim($this->getPostData('nombre') ?? '');
    $email = trim($this->getPostData('email') ?? '');
    $phone = trim($this->getPostData('telefono') ?? '');
    $subject = trim($this->getPostData('asunto') ?? '');
    $message = trim($this->getPostData('mensaje') ?? '');
    
    // Log received data for debugging
    error_log("Contact form received - Name: " . ($name ? 'PROVIDED' : 'EMPTY') . 
             ", Email: " . ($email ? 'PROVIDED' : 'EMPTY') . 
             ", Subject: " . ($subject ? 'PROVIDED' : 'EMPTY') . 
             ", Message: " . ($message ? 'PROVIDED' : 'EMPTY'));
    
    // Basic validation
    $errors = $this->validateContactForm($name, $email, $phone, $subject, $message);
    
    if (!empty($errors)) {
        error_log("Contact form validation errors: " . implode(', ', $errors));
        $this->redirect('index.php?page=contact&error=' . urlencode(implode(', ', $errors)));
        return;
    }
    
    // Save to database
    $contact = new Contact();
    $result = $contact->save($name, $email, $phone, $subject, $message);
    
    if ($result) {
        // Try to send email
        $emailSent = $this->sendEmailNotification($name, $email, $phone, $subject, $message);
        
        // Always create backup log
        $this->sendBackupNotification($name, $email, $phone, $subject, $message);
        
        if (!$emailSent) {
            error_log("ALERT: Contact form saved to database but email failed to send!");
        }
        
        $this->redirect('index.php?page=contact&success=1');
    } else {
        error_log("CRITICAL: Contact form failed to save to database!");
        $this->redirect('index.php?page=contact&error=save_failed');
    }
}
}
?>