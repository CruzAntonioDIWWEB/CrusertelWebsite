<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crusertel - Contáctanos</title>
    <link rel="stylesheet" href="assets/css/estilo_contacto.css">
</head>
<body>
    <header>
    <div class="container header-content">
        <div class="logo">
            <img src="assets/img/logo/imagen_logo_crusertel.jpg" alt="Logo Crusertel">
        </div>
        <nav>
            <ul>
                <li><a href="pagina_Inicio.html">Inicio</a></li>
                <li><a href="pagina_servicios.html">Servicios</a></li>
                <li><a href="pagina_faq.html">Preguntas Frecuentes</a></li>
                <li><a href="pagina_contacto.html">Contacto</a></li>
                <li><a href="pagina_unete.html">Únete</a></li>
            </ul>
        </nav>
    </div>
</header>
    <main>
        <section id="contact-intro">
            <div class="container">
                <h2>Contáctanos</h2>
                <p><b>¿Tienes alguna pregunta, sugerencia o necesitas asistencia? No dudes en ponerte en contacto con nosotros.</b></p>
            </div>
        </section>

        <section id="contact-info">
            <div class="contact-cards">
    <div class="contact-card">
        <h4>📞 Teléfono</h4>
        <p><a href="tel:+34958016411">958 01 64 11</a>
    </div>
    <div class="contact-card">
        <h4>✉️ Email</h4>
        <p><a href="mailto:info@crusertel.es">info@crusertel.es</a></p>
    </div>
    <div class="contact-card">
        <h4>📍 Dirección</h4>
        <p>Calle Arabial 45 local 18<br>18003 Granada, España</p>
    </div>
</div>
        </section>

        <form id="contact-form" method="POST">
            <div class="container">
                <h3>Envíanos un Mensaje</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Asunto:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Enviar Mensaje</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="fade-in-up-initial">
    <div class="container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; font-size: 0.95em;">
        <span>📩 info@crusertel.es</span>
        <span>|</span>
        <span>📍 Calle Arabial 45 local 18</span>
        <span>|</span>
        <span>📞 958 01 64 11</span>
    </div>
</footer>

    <script src="funciones_contacto.js"></script>
</body>
</html>