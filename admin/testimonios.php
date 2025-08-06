<?php
session_start();
require '../includes/config.php';

// Verificar autenticación y rol de admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testimonio_id = $_POST['id'];
    
    if (isset($_POST['aprobar'])) {
        $db->prepare("UPDATE testimonios SET aprobado = 1 WHERE id = ?")->execute([$testimonio_id]);
    } elseif (isset($_POST['rechazar'])) {
        $db->prepare("DELETE FROM testimonios WHERE id = ?")->execute([$testimonio_id]);
    } elseif (isset($_POST['destacar'])) {
        $db->prepare("UPDATE testimonios SET destacado = 1 WHERE id = ?")->execute([$testimonio_id]);
    } elseif (isset($_POST['quitar_destacado'])) {
        $db->prepare("UPDATE testimonios SET destacado = 0 WHERE id = ?")->execute([$testimonio_id]);
    }
}

// Obtener testimonios
$testimonios = $db->query("
    SELECT t.*, s.email 
    FROM testimonios t
    JOIN suscriptores s ON t.usuario_id = s.id
    ORDER BY t.aprobado ASC, t.fecha_creacion DESC
")->fetchAll();

include '../includes/header-admin.php';
?>

<div class="admin-container">
    <h1>Gestión de Testimonios</h1>
    
    <div class="admin-filters">
        <a href="?filtro=todos" class="btn-small">Todos</a>
        <a href="?filtro=pendientes" class="btn-small">Pendientes</a>
        <a href="?filtro=aprobados" class="btn-small">Aprobados</a>
        <a href="?filtro=destacados" class="btn-small">Destacados</a>
    </div>
    
    <div class="testimonios-admin-list">
        <?php if (empty($testimonios)): ?>
            <p>No hay testimonios por revisar.</p>
        <?php else: ?>
            <?php foreach ($testimonios as $testimonio): ?>
            <div class="testimonio-admin-card <?php echo $testimonio['aprobado'] ? 'aprobado' : 'pendiente'; ?>">
                <div class="testimonio-admin-content">
                    <p><?php echo nl2br(htmlspecialchars($testimonio['contenido'])); ?></p>
                </div>
                
                <div class="testimonio-admin-info">
                    <div>
                        <strong>Autor:</strong> <?php echo htmlspecialchars($testimonio['email']); ?><br>
                        <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($testimonio['fecha_creacion'])); ?><br>
                        <strong>Votos:</strong> ↑<?php echo $testimonio['votos_positivos']; ?> ↓<?php echo $testimonio['votos_negativos']; ?>
                    </div>
                    
                    <div class="testimonio-admin-actions">
                        <?php if (!$testimonio['aprobado']): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $testimonio['id']; ?>">
                                <button type="submit" name="aprobar" class="btn-small">Aprobar</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $testimonio['id']; ?>">
                                <button type="submit" name="rechazar" class="btn-small btn-danger">Rechazar</button>
                            </form>
                        <?php else: ?>
                            <?php if (!$testimonio['destacado']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $testimonio['id']; ?>">
                                    <button type="submit" name="destacar" class="btn-small">Destacar</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $testimonio['id']; ?>">
                                    <button type="submit" name="quitar_destacado" class="btn-small">Quitar destacado</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer-admin.php'; ?>