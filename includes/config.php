<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Nunca dejes vacío en producción
define('DB_NAME', 'database.db'); //Colocar el nombre de la base de datos correspondiente

// Configuración de correo (usando contraseña de aplicación)
define('MAIL_FROM', 'correo@gmail.com'); // Colocar correo electronico aquí
define('MAIL_PASS', 'xxxx xxxx xxxx xxxx'); // Usa la contraseña de aplicación dada en "https://myaccount.google.com/apppasswords"
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_SECURE', 'tls'); // Añade esto para mayor seguridad

// Conexión a la base de datos
try {
    $db = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    error_log("Error de conexión a BD: " . $e->getMessage());
    die("Error en el sistema. Por favor intenta más tarde.");
}
?>

