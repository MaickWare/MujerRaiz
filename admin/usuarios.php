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

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aprobar'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare("UPDATE suscriptores SET aprobado = 1 WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare("DELETE FROM suscriptores WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['crear'])) {
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $rol = $_POST['rol'];
        
        $stmt = $db->prepare("INSERT INTO suscriptores (email, password, rol, aprobado) VALUES (?, ?, ?, 1)");
        $stmt->execute([$email, $password, $rol]);
    }
}

// Obtener usuarios
$pendientes = isset($_GET['pendientes']) ? "WHERE aprobado = 0" : "";
$usuarios = $db->query("SELECT * FROM suscriptores $pendientes ORDER BY fecha_creacion DESC")->fetchAll();

include '../includes/header-admin.php';
?>

<div class="admin-container">
    <h1><?php echo isset($_GET['pendientes']) ? 'Usuarios Pendientes' : 'Gestión de Usuarios'; ?></h1>
    
    <div class="admin-actions">
        <button id="showUserForm" class="btn">Crear Nuevo Usuario</button>
        
        <div id="userForm" style="display: none; margin-top: 20px;">
            <form method="POST" class="form-admin">
                <h3>Crear Nuevo Usuario</h3>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol">
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <button type="submit" name="crear" class="btn">Crear Usuario</button>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo ucfirst($usuario['rol']); ?></td>
                    <td><?php echo $usuario['aprobado'] ? 'Aprobado' : 'Pendiente'; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])); ?></td>
                    <td class="actions">
                        <?php if (!$usuario['aprobado']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" name="aprobar" class="btn-small">Aprobar</button>
                        </form>
                        <?php endif; ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" name="eliminar" class="btn-small btn-danger" 
                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('showUserForm').addEventListener('click', function() {
    const form = document.getElementById('userForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});
</script>

<?php include '../includes/footer-admin.php'; ?>