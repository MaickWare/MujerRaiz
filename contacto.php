<?php
require 'includes/config.php';
require 'includes/mailer.php';

$mensaje = '';
$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $asunto = trim($_POST['asunto']);
    $mensaje = trim($_POST['mensaje']);

    // Validación
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $error = "Por favor completa todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor ingresa un correo electrónico válido.";
    } else {
        try {
            // Guardar en base de datos
            $stmt = $db->prepare("INSERT INTO contactos (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $asunto, $mensaje]);

            // Enviar correo
            $cuerpoCorreo = "
                <h2>Nuevo mensaje de contacto</h2>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Asunto:</strong> $asunto</p>
                <p><strong>Mensaje:</strong></p>
                <p>$mensaje</p>
            ";

            enviarCorreo(MAIL_FROM, "Nuevo mensaje de contacto: $asunto", $cuerpoCorreo);

            $mensaje = "¡Gracias por contactarnos! Te responderemos pronto.";
            $nombre = $email = $asunto = $mensaje = ''; // Limpiar campos
        } catch(PDOException $e) {
            $error = "Ocurrió un error al enviar tu mensaje. Por favor intenta más tarde.";
            error_log("Error en contacto: " . $e->getMessage());
        }
    }
}

include 'includes/header.php';
?>

<main class="container">
    <section class="contacto">
        <h2>Contáctanos</h2>
        <p>¿Tienes preguntas o comentarios? Escríbenos y te responderemos lo antes posible.</p>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje): ?>
            <div class="alert success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <form action="contacto.php" method="POST" class="form-contacto">
            <div class="form-group">
                <label for="nombre">Nombre completo *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico *</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" value="<?php echo isset($asunto) ? htmlspecialchars($asunto) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="mensaje">Mensaje *</label>
                <textarea id="mensaje" name="mensaje" rows="6" required><?php echo isset($mensaje) ? htmlspecialchars($mensaje) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="cta-button">Enviar mensaje</button>
        </form>
    </section>
    
    <section class="info-contacto">
        <h3>Otras formas de contacto</h3>
        <div class="info-container">
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <p>softwaremaick@gmail.com</p>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <p>+57 322 332 4550</p>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Huila, Colombia</p>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>