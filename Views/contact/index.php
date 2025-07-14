<section id="contact-intro">
    <div class="container">
        <h2>Contáctanos</h2>
        <p><b>¿Tienes alguna pregunta, sugerencia o necesitas asistencia? Estamos aquí para ayudarte. Nuestro equipo de expertos está disponible para ofrecerte el mejor asesoramiento personalizado.</b></p>
    </div>
</section>

<section id="contact-form">
    <div class="container">
        <form action="guardar_contacto.php" method="POST" class="contact-form">
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
    </div>
</section>

<section id="contact-info">
    <div class="container">
        <div class="contact-info-grid">
            <div class="contact-item">
                <h3>📍 Ubicación</h3>
                <p>Calle Arabial 45 local 18<br>Granada, España</p>
            </div>
            <div class="contact-item">
                <h3>📞 Teléfono</h3>
                <p>958 01 64 11</p>
            </div>
            <div class="contact-item">
                <h3>📩 Email</h3>
                <p>info@crusertel.es</p>
            </div>
        </div>
    </div>
</section>