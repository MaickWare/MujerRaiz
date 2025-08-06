<?php
session_start();
require '../includes/config.php';

// Verificar autenticación y rol de admin
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $db->prepare("SELECT rol FROM suscriptores WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if ($usuario['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Obtener estadísticas
$contactos = $db->query("SELECT COUNT(*) FROM contactos")->fetchColumn();
$usuarios = $db->query("SELECT COUNT(*) FROM suscriptores")->fetchColumn();
$pendientes = $db->query("SELECT COUNT(*) FROM suscriptores WHERE aprobado = 0")->fetchColumn();

include '../includes/header-admin.php';
?>

<div class="admin-container">
    <h1>Panel de Administración</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Mensajes de Contacto</h3>
            <p><?php echo $contactos; ?></p>
            <a href="contactos.php" class="btn">Ver todos</a>
        </div>
        
        <div class="stat-card">
            <h3>Usuarios Registrados</h3>
            <p><?php echo $usuarios; ?></p>
            <a href="usuarios.php" class="btn">Gestionar</a>
        </div>
        
        <div class="stat-card">
            <h3>Usuarios Pendientes</h3>
            <p><?php echo $pendientes; ?></p>
            <a href="usuarios.php?pendientes=1" class="btn">Revisar</a>
        </div>
    </div>
    
    <div class="recent-activity">
        <h2>Actividad Reciente</h2>
        <?php
        $actividad = $db->query("
            SELECT 'contacto' as tipo, fecha_creacion as fecha, nombre, '' as email 
            FROM contactos 
            UNION 
            SELECT 'usuario' as tipo, fecha_creacion as fecha, '' as nombre, email 
            FROM suscriptores 
            ORDER BY fecha DESC 
            LIMIT 5
        ")->fetchAll();
        
        if ($actividad): ?>
            <ul class="activity-list">
                <?php foreach ($actividad as $item): ?>
                <li>
                    <span class="fecha"><?php echo date('d/m/Y H:i', strtotime($item['fecha'])); ?></span>
                    <?php if ($item['tipo'] == 'contacto'): ?>
                        Nuevo mensaje de <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                    <?php else: ?>
                        Nuevo usuario: <strong><?php echo htmlspecialchars($item['email']); ?></strong>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay actividad reciente</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer-admin.php'; ?>