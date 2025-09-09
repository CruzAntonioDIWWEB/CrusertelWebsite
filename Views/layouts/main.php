<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crusertel - Inicio</title>
    <!-- Main global styles -->
    <link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <?php
    // Dynamically include section-specific CSS
    $page = $_GET['page'] ?? 'home';
    if ($page === 'contact') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_contacto.css">';
    } elseif ($page === 'faq') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_faq.css">';
    } elseif ($page === 'services') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_servicios.css">';
    } elseif ($page === 'tarifs') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_tarifas.css">';
    } elseif ($page === 'joinUs') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_unete.css">';
    } elseif ($page === 'home') {
        echo '<link rel="stylesheet" href="/dashboard/CrusertelWebsite/assets/styles/estilo_inicio.css">';
    }
    ?>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?php echo $content; ?>
    </main>

    <?php include 'footer.php'; ?>

    <script src="/dashboard/CrusertelWebsite/assets/js/main.js"></script>
</body>
</html>