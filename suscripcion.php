<?php
require 'includes/config.php';
require 'includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    try {
        // Verificar si el email ya existe
        $stmt = $db->prepare("SELECT id FROM suscriptores WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $mensaje = "Este correo ya está registrado. Te hemos reenviado el enlace.";
        } else {
            // Insertar nuevo suscriptor
            $stmt = $db->prepare("INSERT INTO suscriptores (email) VALUES (?)");
            $stmt->execute([$email]);
            $mensaje = "¡Gracias por suscribirte! Te hemos enviado el enlace a tu correo.";
        }
        
        // Enviar correo con el enlace (usando mailer.php)
        $asunto = "🎀 Acceso a los módulos de empoderamiento femenino";
        
        // Cuerpo del correo con diseño responsivo
        $cuerpo = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulos de Empoderamiento</title>
    <style type="text/css">
        /* Estilos base */
        body, html {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f9f3f3;
        }
        
        /* Contenedor principal */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Cabecera */
        .email-header {
            background-color: #8a4f5e;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        
        /* Contenido */
        .email-content {
            padding: 30px;
        }
        
        .email-content h2 {
            color: #6d3b47;
            margin-top: 0;
            font-size: 20px;
        }
        
        .email-content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        /* Botón */
        .btn {
            display: inline-block;
            background-color: #e91e63;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        
        /* Pie de página */
        .email-footer {
            background-color: #f0d8d8;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6d3b47;
        }
        
        /* Imagen responsiva */
        .responsive-img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 4px;
        }
        
        /* Media queries para móviles */
        @media screen and (max-width: 480px) {
            .email-container {
                border-radius: 0;
            }
            
            .email-header, .email-content, .email-footer {
                padding: 20px 15px;
            }
            
            .email-header h1 {
                font-size: 20px;
            }
            
            .btn {
                display: block;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Cabecera -->
        <div class="email-header">
            <h1>Mujer Raíz</h1>
        </div>
        
        <!-- Contenido principal -->
        <div class="email-content">
            <h2>¡Bienvenida a nuestra comunidad!</h2>
            
            <p>Hola,</p>
            
            <p>Gracias por unirte a nuestro programa de <strong>empoderamiento femenino</strong>. Estamos emocionadas de acompañarte en este viaje de crecimiento personal.</p>
            
            <p>A continuación encontrarás el enlace para acceder a todos nuestros módulos educativos:</p>
            
            <center>
                <a href="http://localhost/mujerraiz/modulos.php" class="btn">Acceder a los módulos</a>
            </center>
            
            <p>Cada módulo ha sido cuidadosamente diseñado para ayudarte a:</p>
            <ul>
                <li>Descubrir tu poder interior</li>
                <li>Fortalecer tu autoestima</li>
                <li>Establecer relaciones saludables</li>
                <li>Alcanzar tu máximo potencial</li>
            </ul>
            
            <img src="https://imgur.com/a/4PxSB6P" alt="Mujer Inmarcesible" class="responsive-img" style="max-width: 200px;">
        </div>
        
        <!-- Pie de página -->
        <div class="email-footer">
            <p>Con cariño,<br>
            <strong>El equipo de Mujer Raíz</strong></p>
            
            <p style="font-size: 12px; margin-top: 15px;">
                Si no solicitaste este correo, por favor ignóralo.<br>
                © '.date('Y').' Mujer Raíz - Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
        ';

        enviarCorreo($email, $asunto, $cuerpo);
        
    } catch(PDOException $e) {
        $mensaje = "Ocurrió un error al procesar tu suscripción. Por favor intenta más tarde.";
        error_log("Error en suscripción: " . $e->getMessage());
    }
    
    // Redirigir con mensaje
    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>