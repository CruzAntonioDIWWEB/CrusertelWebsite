<?php
// Error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if needed
session_start();

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
            require_once '../Controllers/ContactController.php';
            $controller = new \Controllers\ContactController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->submitContactForm();
            } else {
                $controller->showContactForm();
            }
            break;
            
        case 'faq':
            require_once '../Controllers/BaseController.php';
            $controller = new \BaseController\BaseController();
            $controller->loadView('faq/index', ['title' => 'FAQ - Crusertel']);
            break;
            
        case 'services':
            require_once '../Controllers/BaseController.php';
            $controller = new \BaseController\BaseController();
            $controller->loadView('services/index', ['title' => 'Servicios - Crusertel']);
            break;
            
        case 'tarifs':
            require_once '../Controllers/BaseController.php';
            $controller = new \BaseController\BaseController();
            $controller->loadView('tarifs/index', ['title' => 'Tarifas - Crusertel']);
            break;
            
        case 'joinUs':
            require_once '../Controllers/BaseController.php';
            $controller = new \BaseController\BaseController();
            $controller->loadView('joinUs/index', ['title' => 'Únete - Crusertel']);
            break;
            
        case 'home':
        default:
            require_once '../Controllers/BaseController.php';
            $controller = new \BaseController\BaseController();
            $controller->loadView('home/index', ['title' => 'Inicio - Crusertel']);
            break;
    }
} catch (Exception $e) {
    // Log error
    error_log("Error in index.php: " . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    require_once '../Controllers/BaseController.php';
    $controller = new \BaseController\BaseController();
    $controller->loadView('error/500', [
        'title' => 'Error - Crusertel',
        'message' => 'Ha ocurrido un error interno. Por favor, inténtalo más tarde.'
    ]);
}
?>