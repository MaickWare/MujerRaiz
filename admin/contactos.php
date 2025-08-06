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

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM contactos WHERE id = ?");
    $stmt->execute([$id]);
}

// Obtener mensajes
$contactos = $db->query("SELECT * FROM contactos ORDER BY fecha_creacion DESC")->fetchAll();

include '../includes/header-admin.php';
?>

<div class="admin-container">
    <h1>Mensajes de Contacto</h1>
    
    <?php if (empty($contactos)): ?>
        <p>No hay mensajes de contacto.</p>
    <?php else: ?>
        <div class="contactos-list">
            <?php foreach ($contactos as $contacto): ?>
            <div class="contacto-card">
                <div class="contacto-header">
                    <h3><?php echo htmlspecialchars($contacto['nombre']); ?></h3>
                    <span class="fecha"><?php echo date('d/m/Y H:i', strtotime($contacto['fecha_creacion'])); ?></span>
                </div>
                
                <div class="contacto-info">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($contacto['email']); ?></p>
                    <?php if (!empty($contacto['asunto'])): ?>
                    <p><strong>Asunto:</strong> <?php echo htmlspecialchars($contacto['asunto']); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="contacto-mensaje">
                    <p><?php echo nl2br(htmlspecialchars($contacto['mensaje'])); ?></p>
                </div>
                
                <form method="POST" class="contacto-actions">
                    <input type="hidden" name="id" value="<?php echo $contacto['id']; ?>">
                    <button type="submit" name="eliminar" class="btn-small btn-danger"
                            onclick="return confirm('¿Estás seguro de eliminar este mensaje?')">
                        Eliminar
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer-admin.php'; ?>