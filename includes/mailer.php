<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Necesitarás instalar PHPMailer via Composer

function enviarCorreo($destinatario, $asunto, $cuerpo, $adjunto = null) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_FROM;
        $mail->Password = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        
        // Remitente y destinatario
        $mail->setFrom(MAIL_FROM, 'Mujer Raíz');
        $mail->addAddress($destinatario);
        
        // Contenido HTML
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        $mail->AltBody = strip_tags($cuerpo); // Versión texto plano
        
        // Adjuntar imagen si existe
        if ($adjunto && file_exists($adjunto['ruta'])) {
            // Detectar tipo MIME si no se especifica
            $tipo = isset($adjunto['tipo']) ? $adjunto['tipo'] : mime_content_type($adjunto['ruta']);
            $mail->addEmbeddedImage(
                $adjunto['ruta'],
                $adjunto['cid'],
                isset($adjunto['nombre']) ? $adjunto['nombre'] : basename($adjunto['ruta']),
                'base64',
                $tipo
            );
        }
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
?>
