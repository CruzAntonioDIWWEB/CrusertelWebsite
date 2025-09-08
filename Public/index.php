<?php
// Get requested page from URL, default to 'home'
$page = $_GET['page'] ?? 'home';

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
        $controller->loadView('faq/index');
        break;
    case 'services':
        require_once '../Controllers/BaseController.php';
        $controller = new \BaseController\BaseController();
        $controller->loadView('services/index');
        break;
    case 'tarifs':
        require_once '../Controllers/BaseController.php';
        $controller = new \BaseController\BaseController();
        $controller->loadView('tarifs/index');
        break;
    case 'joinUs':
        require_once '../Controllers/BaseController.php';
        $controller = new \BaseController\BaseController();
        $controller->loadView('joinUs/index');
        break;
    case 'home':
    default:
        require_once '../Controllers/BaseController.php';
        $controller = new \BaseController\BaseController();
        $controller->loadView('home/index');
        break;
}
?>