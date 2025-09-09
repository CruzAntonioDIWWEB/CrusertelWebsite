<section id="error-404" class="error-section">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Página no encontrada</h2>
            <p class="error-message">
                <?php echo isset($message) ? htmlspecialchars($message) : 'Lo sentimos, la página que buscas no existe.'; ?>
            </p>
            <div class="error-actions">
                <a href="index.php?page=home" class="btn btn-primary">Volver al inicio</a>
                <a href="index.php?page=contact" class="btn btn-secondary">Contactar</a>
            </div>
        </div>
    </div>
</section>