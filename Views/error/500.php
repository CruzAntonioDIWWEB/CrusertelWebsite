<section id="error-500" class="error-section">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">500</h1>
            <h2 class="error-title">Error interno del servidor</h2>
            <p class="error-message">
                <?php echo isset($message) ? htmlspecialchars($message) : 'Ha ocurrido un error interno. Por favor, inténtalo más tarde.'; ?>
            </p>
            <div class="error-actions">
                <a href="index.php?page=home" class="btn btn-primary">Volver al inicio</a>
                <a href="index.php?page=contact" class="btn btn-secondary">Reportar problema</a>
            </div>
        </div>
    </div>
</section>