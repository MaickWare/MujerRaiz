<?php
session_start();
require 'includes/config.php';

// Verificar si el usuario está autenticado y aprobado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $db->prepare("SELECT aprobado FROM suscriptores WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario || !$usuario['aprobado']) {
    header("Location: pendiente.php");
    exit();
}

// Resto del código de los módulos...
include 'includes/header.php';
?>

<main class="container">
    <h2>Módulos de Empoderamiento</h2>
    <p class="intro-modulos">Explora nuestros 8 módulos diseñados para ayudarte a recuperar tu confianza y encontrar tu camino.</p>
    
    <div class="modulo-container">
        <?php
        $modulos = [
            [
                'titulo' => 'Autoconocimiento',
                'descripcion' => 'Aprende a reconectar contigo misma, identificar tus fortalezas y reconocer tu valor.',
                'video' => 'https://www.youtube.com/embed/ejemplo1'
            ],
            [
                'titulo' => 'Autoestima',
                'descripcion' => 'Técnicas para construir una autoestima saludable y amor propio.',
                'video' => 'https://www.youtube.com/embed/ejemplo2'
            ],
            [
                'titulo' => 'Manejo emocional',
                'descripcion' => 'Cómo entender y gestionar tus emociones de manera constructiva.',
                'video' => 'https://www.youtube.com/embed/ejemplo3'
            ],
            [
                'titulo' => 'Establecimiento de límites',
                'descripcion' => 'Aprende a decir no y proteger tu espacio emocional.',
                'video' => 'https://www.youtube.com/embed/ejemplo4'
            ],
            [
                'titulo' => 'Relaciones saludables',
                'descripcion' => 'Cómo identificar y cultivar relaciones que te nutran.',
                'video' => 'https://youtu.be/zo4g2Ue-zEo?si=eC1sdwIcGXc6Dc-P'
            ],
            [
                'titulo' => 'Crecimiento personal',
                'descripcion' => 'Herramientas para tu desarrollo continuo y realización.',
                'video' => 'https://www.youtube.com/embed/ejemplo6'
            ],
            [
                'titulo' => 'Empoderamiento laboral',
                'descripcion' => 'Cómo destacar en el ámbito profesional con confianza.',
                'video' => 'https://www.youtube.com/embed/ejemplo7'
            ],
            [
                'titulo' => 'Bienestar integral',
                'descripcion' => 'Cuidado físico, mental y espiritual para una vida plena.',
                'video' => 'https://www.youtube.com/embed/ejemplo8'
            ]
        ];
        
        foreach ($modulos as $modulo) {
            echo '<div class="modulo">';
            echo '<h3>' . $modulo['titulo'] . '</h3>';
            echo '<p>' . $modulo['descripcion'] . '</p>';
            echo '<div class="video-container">';
            echo '<iframe width="560" height="315" src="' . $modulo['video'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>