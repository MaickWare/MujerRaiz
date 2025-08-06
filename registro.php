<?php
require 'includes/config.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validación
    if (empty($email) || empty($password)) {
        $error = "Por favor completa todos los campos.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor ingresa un correo electrónico válido.";
    } else {
        // Verificar si el email ya existe
        $stmt = $db->prepare("SELECT id FROM suscriptores WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Este correo electrónico ya está registrado.";
        } else {
            // Crear usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO suscriptores (email, password, aprobado) VALUES (?, ?, 0)");
            $stmt->execute([$email, $hashed_password]);
            
            $mensaje = "¡Registro exitoso! Tu cuenta está pendiente de aprobación por un administrador.";
        }
    }
}

include 'includes/header.php';
?>

<main class="container">
    <section class="auth-form">
        <h2>Registro de Usuario</h2>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje): ?>
            <div class="alert success"><?php echo $mensaje; ?></div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">Registrarse</button>
            </form>
            
            <p class="auth-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>