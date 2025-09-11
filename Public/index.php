<?php
/**
 * Crusertel Website Entry Point
 * Final version with Composer and proper error handling
 */

// Bootstrap the application
require_once '../bootstrap.php';

// Get requested page from URL, default to 'home'
$page = $_GET['page'] ?? 'home';

// Validate page parameter to prevent path traversal
$allowedPages = ['home', 'contact', 'faq', 'services', 'tarifs', 'joinUs'];
if (!in_array($page, $allowedPages)) {
    $page = 'home';
}

try {
    // Route to the correct controller/view
    switch ($page) {
        case 'contact':
            $controller = new \Controllers\ContactController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                error_log("Contact form POST request received");
                $controller->submitContactForm();
            } else {
                $controller->showContactForm();
            }
            break;
            
        case 'faq':
            $controller = new \Controllers\BaseController();
            $controller->loadView('faq/index', ['title' => 'FAQ - Crusertel']);
            break;
            
        case 'services':
            $controller = new \BaseController\BaseController();
            $controller->loadView('services/index', ['title' => 'Servicios - Crusertel']);
            break;
            
        case 'tarifs':
            $controller = new \BaseController\BaseController();
            $controller->loadView('tarifs/index', ['title' => 'Tarifas - Crusertel']);
            break;
            
        case 'joinUs':
            $controller = new \BaseController\BaseController();
            $controller->loadView('joinUs/index', ['title' => 'Únete - Crusertel']);
            break;
            
        case 'home':
        default:
            $controller = new \BaseController\BaseController();
            $controller->loadView('home/index', ['title' => 'Inicio - Crusertel']);
            break;
    }
} catch (Exception $e) {
    // Enhanced error logging
    error_log("Application Error: " . $e->getMessage());
    error_log("File: " . $e->getFile());
    error_log("Line: " . $e->getLine());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Show error page
    http_response_code(500);
    
    try {
        $controller = new \BaseController\BaseController();
        $errorMessage = ($_ENV['APP_DEBUG'] === 'true') 
            ? 'Error: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ' line ' . $e->getLine()
            : 'Ha ocurrido un error interno. Por favor, inténtalo más tarde.';
            
        $controller->loadView('error/500', [
            'title' => 'Error - Crusertel',
            'message' => $errorMessage
        ]);
    } catch (Exception $errorException) {
        // Fallback if even error page fails
        echo '<!DOCTYPE html><html><head><title>Error - Crusertel</title></head><body>';
        echo '<h1>Error del Sistema</h1>';
        echo '<p>Ha ocurrido un error interno. Por favor, contacta con el administrador.</p>';
        echo '<p><a href="index.php?page=home">Volver al inicio</a></p>';
        echo '</body></html>';
    }
}
?>