<?php include 'includes/header.php'; ?>

<main class="container">
    <section class="hero">
        <h2>Descubre tu poder interior</h2>
        <p>Cada mujer tiene una fuerza increíble dentro de sí. Permítenos ayudarte a encontrarla.</p>
        <a href="#suscribete" class="cta-button">Quiero comenzar</a>
    </section>

    <section class="mensajes">
        <h2>Mensajes para ti</h2>
        <div class="mensaje-container">
            <div class="mensaje">
                <h3>Eres más fuerte de lo que crees</h3>
                <p>Los desafíos que enfrentas hoy están preparándote para el éxito de mañana.</p>
            </div>
            <div class="mensaje">
                <h3>Tus sentimientos son válidos</h3>
                <p>No minimices lo que sientes. Reconoce tus emociones y trabaja con ellas, no contra ellas.</p>
            </div>
            <div class="mensaje">
                <h3>El progreso no es lineal</h3>
                <p>Está bien tener días malos. Lo importante es no quedarse allí.</p>
            </div>
        </div>
    </section>

    <section id="suscribete" class="suscripcion">
        <h2>¿Quieres acceder a nuestros módulos de empoderamiento?</h2>
        <p>Déjanos tu correo y te enviaremos el enlace con acceso a todos los materiales.</p>
        
        <form action="suscripcion.php" method="POST">
            <input type="email" name="email" placeholder="Tu correo electrónico" required>
            <button type="submit" class="cta-button">Enviar enlace</button>
        </form>
    </section>

<?php include 'includes/footer.php'; ?>