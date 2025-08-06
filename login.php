<?php
session_start();
require 'includes/config.php';

$error = '';
// Verificar si el usuario ya está autenticado
if (isset($_SESSION['usuario_id'])) {
        
    // Verificar credenciales
    if ($_POST['email'] === $email && $_POST['password'] === $password) {
        // Obtener datos completos del usuario de la base de datos
        $stmt = $db->prepare("SELECT id, email, rol FROM suscriptores WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            header("Location: admin/index.php");
            exit();
        }
    }

    // Si las credenciales no coinciden
    $error = "Correo electrónico o contraseña incorrectos";
}

include 'includes/header.php';
?>

<main class="container">
    <section class="auth-form">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Ingresar</button>
        </form>
        
        <p class="auth-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </section>
</main>

<?php include 'includes/footer.php'; ?>