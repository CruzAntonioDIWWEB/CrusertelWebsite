<?php
/**
 * IONOS Email Test Script
 * Run: php test_ionos_email.php
 */

require_once 'bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "=== IONOS SMTP Connection Test ===\n\n";

try {
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = $_ENV['SMTP_SECURITY'];
    $mail->Port = (int) $_ENV['SMTP_PORT'];
    
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    $mail->Debugoutput = 'echo';
    
    echo "Configuration:\n";
    echo "- Host: " . $_ENV['SMTP_HOST'] . "\n";
    echo "- Port: " . $_ENV['SMTP_PORT'] . "\n";
    echo "- Security: " . $_ENV['SMTP_SECURITY'] . "\n";
    echo "- Username: " . $_ENV['SMTP_USERNAME'] . "\n";
    echo "- Password: " . (strlen($_ENV['SMTP_PASSWORD']) > 0 ? str_repeat('*', strlen($_ENV['SMTP_PASSWORD'])) : 'NOT SET') . "\n\n";
    
    echo "Testing SMTP connection...\n";
    
    // Test connection
    if ($mail->smtpConnect()) {
        echo "\n✓ SUCCESS: SMTP connection established!\n";
        $mail->smtpClose();
        
        // Now test sending an actual email
        echo "\nTesting email sending...\n";
        
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Turn off debug for clean output
        
        $mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SITE_NAME']);
        $mail->addAddress('info@crusertel.es', 'Crusertel Test');
        
        $mail->isHTML(false);
        $mail->Subject = 'Test de configuracion SMTP - ' . date('Y-m-d H:i:s');
        $mail->Body = "Este es un email de prueba del sistema SMTP de Crusertel.\n\n" .
                      "Configuracion utilizada:\n" .
                      "- Servidor: " . $_ENV['SMTP_HOST'] . "\n" .
                      "- Puerto: " . $_ENV['SMTP_PORT'] . "\n" .
                      "- Seguridad: " . $_ENV['SMTP_SECURITY'] . "\n\n" .
                      "Si recibes este email, la configuracion funciona correctamente.\n\n" .
                      "Fecha: " . date('Y-m-d H:i:s');
        
        if ($mail->send()) {
            echo "✓ SUCCESS: Test email sent successfully!\n";
            echo "Check your inbox at info@crusertel.es\n";
        } else {
            echo "✗ ERROR: Failed to send test email\n";
        }
        
    } else {
        echo "\n✗ ERROR: Could not connect to SMTP server\n";
    }
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Error details: " . $mail->ErrorInfo . "\n";
}

echo "\n=== Test completed ===\n";
echo "Remember to delete this test file after verification!\n";
?>