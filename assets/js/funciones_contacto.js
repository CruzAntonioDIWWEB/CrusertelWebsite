document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };

            try {
                const response = await fetch('https://script.google.com/macros/s/AKfycbywBsJVrDgbcgR5GQiCDg02fpSr2mUu3FtfVCrtw-2yDtVbPdmZIt1afhWi7QdzZhIq/exec', {
                    method: 'POST',
                    mode: 'no-cors', // 👈 necesario para evitar errores de CORS
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                alert("Mensaje enviado correctamente.");
                contactForm.reset();
            } catch (error) {
                alert("Hubo un error al enviar tu mensaje.");
                console.error(error);
            }
        });
    }
});
// Este bloque ya debería estar en tu archivo
const allAnimated = document.querySelectorAll(
    '.fade-in-up-initial, .fade-in-down-initial, .slide-in-from-left-initial, .pop-in-initial'
);

allAnimated.forEach((el, index) => {
    setTimeout(() => {
        el.classList.add('animated-element');
    }, 300 + index * 150);
});