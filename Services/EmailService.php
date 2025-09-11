<?php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class EmailService {
    private PHPMailer $mail;
    private Logger $logger;
    
    public function __construct() {
        $this->initializeLogger();
        $this->initializePHPMailer();
    }
    
    private function initializeLogger(): void {
        $this->logger = new Logger('email');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/email.log', Logger::INFO));
    }
    
    private function initializePHPMailer(): void {
        $this->mail = new PHPMailer(true);
        
        try {
            // Server settings from environment
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['SMTP_USERNAME'];
            $this->mail->Password = $_ENV['SMTP_PASSWORD'];
            $this->mail->SMTPSecure = $_ENV['SMTP_SECURITY'];
            $this->mail->Port = (int) $_ENV['SMTP_PORT'];
            
            // Debugging based on environment
            $this->mail->SMTPDebug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true' ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
            $this->mail->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            $this->logger->error('PHPMailer configuration error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    public function sendContactFormEmail(array $contactData): bool {
        try {
            // Reset recipients
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearReplyTos();
            
            // Recipients
            $this->mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SITE_NAME'] . ' - Formulario de Contacto');
            $this->mail->addAddress($_ENV['CONTACT_EMAIL'], $_ENV['SITE_NAME']);
            $this->mail->addReplyTo($contactData['email'], $contactData['name']);
            
            // Content
            $this->mail->isHTML(false); // Plain text for better deliverability
            $this->mail->Subject = 'Nuevo mensaje del formulario de contacto - ' . $contactData['subject'];
            $this->mail->Body = $this->buildEmailBody($contactData);
            
            $result = $this->mail->send();
            
            if ($result) {
                $this->logger->info('Contact email sent successfully', [
                    'recipient' => $_ENV['CONTACT_EMAIL'],
                    'subject' => $contactData['subject'],
                    'from' => $contactData['email'],
                    'name' => $contactData['name']
                ]);
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            $this->logger->error('Failed to send contact email', [
                'error' => $e->getMessage(),
                'contact_data' => [
                    'name' => $contactData['name'] ?? 'N/A',
                    'email' => $contactData['email'] ?? 'N/A',
                    'subject' => $contactData['subject'] ?? 'N/A'
                ]
            ]);
            
            // Log additional debugging info in development
            if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
                error_log('Email Service Error Details: ' . $e->getMessage());
                error_log('PHPMailer ErrorInfo: ' . $this->mail->ErrorInfo);
            }
            
            return false;
        }
    }
    
    private function buildEmailBody(array $data): string {
        $body = "=== NUEVO MENSAJE DEL FORMULARIO DE CONTACTO ===\n\n";
        $body .= "Datos del remitente:\n";
        $body .= "• Nombre: " . ($data['name'] ?? 'No proporcionado') . "\n";
        $body .= "• Email: " . ($data['email'] ?? 'No proporcionado') . "\n";
        $body .= "• Teléfono: " . ($data['phone'] ?? 'No proporcionado') . "\n";
        $body .= "• Asunto: " . ($data['subject'] ?? 'Sin asunto') . "\n";
        $body .= "• Fecha: " . date('d/m/Y H:i:s') . "\n\n";
        
        $body .= "=== MENSAJE ===\n";
        $body .= ($data['message'] ?? 'Sin mensaje') . "\n\n";
        
        $body .= "=== INFORMACIÓN TÉCNICA ===\n";
        $body .= "• IP del remitente: " . ($_SERVER['REMOTE_ADDR'] ?? 'Desconocida') . "\n";
        $body .= "• Navegador: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido') . "\n";
        $body .= "• Referencia: " . ($_SERVER['HTTP_REFERER'] ?? 'Directo') . "\n";
        $body .= "• Servidor: " . ($_SERVER['HTTP_HOST'] ?? 'crusertel.es') . "\n";
        $body .= "• Enviado desde: Sistema de contacto Crusertel v2.0\n";
        
        return $body;
    }
    
    public function sendConfirmationEmail(array $contactData): bool {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();
            
            $this->mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SITE_NAME']);
            $this->mail->addAddress($contactData['email'], $contactData['name']);
            $this->mail->addReplyTo($_ENV['CONTACT_EMAIL'], $_ENV['SITE_NAME'] . ' - Atención al Cliente');
            
            $this->mail->Subject = 'Confirmación de mensaje recibido - ' . $_ENV['SITE_NAME'];
            $this->mail->Body = $this->buildConfirmationBody($contactData);
            
            $result = $this->mail->send();
            
            if ($result) {
                $this->logger->info('Confirmation email sent', [
                    'recipient' => $contactData['email'],
                    'name' => $contactData['name']
                ]);
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->logger->error('Failed to send confirmation email', [
                'error' => $e->getMessage(),
                'recipient' => $contactData['email'] ?? 'unknown'
            ]);
            return false;
        }
    }
    
    private function buildConfirmationBody(array $data): string {
        return "Estimado/a {$data['name']},\n\n" .
               "Hemos recibido tu mensaje correctamente y te agradecemos por contactar con nosotros.\n\n" .
               "📋 RESUMEN DE TU CONSULTA:\n" .
               "• Asunto: {$data['subject']}\n" .
               "• Fecha de recepción: " . date('d/m/Y a las H:i') . "\n" .
               "• Email de contacto: {$data['email']}\n\n" .
               "⏰ PRÓXIMOS PASOS:\n" .
               "Nuestro equipo revisará tu consulta y se pondrá en contacto contigo en un plazo máximo de 24-48 horas hábiles.\n\n" .
               "Si tienes alguna pregunta urgente, no dudes en llamarnos al {$_ENV['SITE_PHONE']}.\n\n" .
               "Gracias por confiar en Crusertel.\n\n" .
               "---\n" .
               "Atentamente,\n" .
               "El equipo de {$_ENV['SITE_NAME']}\n" .
               "📞 {$_ENV['SITE_PHONE']}\n" .
               "📧 {$_ENV['CONTACT_EMAIL']}\n" .
               "🌐 {$_ENV['APP_URL']}\n" .
               "📍 {$_ENV['SITE_ADDRESS']}";
    }
    
    public function testConnection(): bool {
        try {
            $connected = $this->mail->smtpConnect();
            if ($connected) {
                $this->mail->smtpClose();
                $this->logger->info('SMTP connection test successful');
            }
            return $connected;
        } catch (Exception $e) {
            $this->logger->error('SMTP connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Send emergency notification if regular email fails
     */
    public function sendEmergencyLog(array $contactData, string $error): void {
        $logFile = __DIR__ . '/../logs/contact_emergency.log';
        
        $logEntry = "\n=== CONTACT FORM EMERGENCY LOG - " . date('Y-m-d H:i:s') . " ===\n";
        $logEntry .= "ERROR: $error\n";
        $logEntry .= "Name: " . ($contactData['name'] ?? 'N/A') . "\n";
        $logEntry .= "Email: " . ($contactData['email'] ?? 'N/A') . "\n";
        $logEntry .= "Phone: " . ($contactData['phone'] ?? 'N/A') . "\n";
        $logEntry .= "Subject: " . ($contactData['subject'] ?? 'N/A') . "\n";
        $logEntry .= "Message: " . ($contactData['message'] ?? 'N/A') . "\n";
        $logEntry .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
        $logEntry .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n";
        $logEntry .= "=== END EMERGENCY LOG ===\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        $this->logger->critical('Contact form emergency - email failed', [
            'contact_data' => $contactData,
            'error' => $error
        ]);
    }
}