<section id="contact-form">
    <div class="container">
        <!-- Remove action attribute, let MVC handle it -->
        <form method="POST" class="contact-form" action="index.php?page=contact">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            
            <div class="form-group">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>
            </div>
            
            <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-submit">Enviar Mensaje</button>
        </form>
        
        <!-- Success/Error messages -->
        <div id="form-messages" style="display: none;">
            <div class="alert alert-success" id="success-message">
                Mensaje enviado correctamente. Te contactaremos pronto.
            </div>
            <div class="alert alert-error" id="error-message">
                Error al enviar el mensaje. Por favor, inténtalo de nuevo.
            </div>
        </div>
    </div>
</section>