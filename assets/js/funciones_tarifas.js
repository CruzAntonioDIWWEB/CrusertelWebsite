// Funcionalidad del modal (ampliación de imágenes)
const imagenes = document.querySelectorAll('.tarifa img');
const modal = document.getElementById('modal');
const modalImg = document.getElementById('imgAmpliada');
const cerrar = document.querySelector('.cerrar');

imagenes.forEach(img => {
    img.addEventListener('click', () => {
        modal.style.display = 'block';
        modalImg.src = img.src;
    });
});

cerrar.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', e => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Botón de retorno
const botonVolver = document.createElement('button');
botonVolver.textContent = '⬅ Volver a Inicio';
botonVolver.classList.add('boton-volver');
botonVolver.onclick = () => {
    window.location.href = 'pagina_Inicio.html';
};

document.body.appendChild(botonVolver);