<?php
session_start();
require 'includes/config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = trim($_POST['contenido']);
    $usuario_id = $_SESSION['usuario_id'];
    
    if (empty($contenido)) {
        $_SESSION['error_testimonio'] = "Por favor escribe tu testimonio.";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO testimonios (usuario_id, contenido) VALUES (?, ?)");
            $stmt->execute([$usuario_id, $contenido]);
            
            $_SESSION['mensaje_testimonio'] = "¡Gracias por compartir tu historia! Será revisada antes de publicarse.";
        } catch(PDOException $e) {
            $_SESSION['error_testimonio'] = "Ocurrió un error al enviar tu testimonio.";
        }
    }
}

header("Location: testimonios.php");
exit();
?>