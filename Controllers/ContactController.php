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
        $to = "info@crusertel.es";
        $emailSubject = "Nuevo mensaje del formulario de contacto - " . $subject;
        $body = "Has recibido un nuevo mensaje:\n\n";
        $body .= "Nombre: $name\n";
        $body .= "Email: $email\n";
        $body .= "Teléfono: $phone\n";
        $body .= "Asunto: $subject\n";
        $body .= "Mensaje:\n$message\n\n";
        $body .= "---\n";
        $body .= "Enviado desde: " . $_SERVER['HTTP_HOST'] . "\n";
        $body .= "Fecha: " . date('Y-m-d H:i:s');
        
        $headers = [
            'From: noreply@crusertel.es',
            'Reply-To: ' . $email,
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        $result = mail($to, $emailSubject, $body, implode("\r\n", $headers));
        
        if (!$result) {
            error_log("Failed to send email notification for contact form");
        }
        
        return $result;
    }
}
?>