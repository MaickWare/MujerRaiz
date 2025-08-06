// assets/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar mensaje de suscripción si existe en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    
    if (mensaje) {
        alert(mensaje);
        // Limpiar la URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    // Smooth scrolling para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});