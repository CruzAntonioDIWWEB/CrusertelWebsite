<?php
/**
 * Crusertel Application Bootstrap
 */

// Load Composer autoloader
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('Composer dependencies not installed. Run: composer install');
}

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables (solo si existe .env)
if (file_exists(__DIR__ . '/.env')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        
        // Validate required environment variables
        $dotenv->required([
            'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME',
            'SMTP_HOST', 'SMTP_USERNAME', 'SMTP_PASSWORD',
            'CONTACT_EMAIL', 'SITE_NAME'
        ]);
    } catch (Exception $e) {
        // En desarrollo, mostrar error
        if ($_SERVER['SERVER_NAME'] === 'localhost' || strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false) {
            die('Environment configuration error: ' . $e->getMessage());
        }
        // En producción, log error pero continuar
        error_log('Environment error: ' . $e->getMessage());
    }
}

// Set error reporting
$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 0);
}

// Ensure logs directory exists
$logsDir = __DIR__ . '/logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Set error log
ini_set('error_log', $logsDir . '/php_errors.log');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set('Europe/Madrid');
?>