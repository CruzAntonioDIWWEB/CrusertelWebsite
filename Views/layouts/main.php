<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crusertel - Inicio</title>
    <link rel="stylesheet" href="/dashboard/Crusertel/assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?php 
        // Simple routing - include the right page content
        $page = $_GET['page'] ?? 'home';
        
        switch($page) {
            case 'home':
                include __DIR__ . '/../home/index.php';
                break;
            case 'services':
                include __DIR__ . '/../services/index.php';
                break;
            case 'contact':
                include __DIR__ . '/../contact/index.php';
                break;
            case 'faq':
                include __DIR__ . '/../faq/index.php';
                break;
            case 'tarifas':
                include __DIR__ . '/../tarifas/index.php';
                break;
            case 'unete':
                include __DIR__ . '/../unete/index.php';
                break;
            default:
                include __DIR__ . '/../home/index.php';
        }
        ?>
    </main>

    <?php include 'footer.php'; ?>

    <script src="/dashboard/Crusertel/assets/js/main.js"></script>
</body>
</html>