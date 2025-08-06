<?php
session_start();
require 'includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Debes iniciar sesión para votar']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testimonio_id = $_POST['id'];
    $tipo = $_POST['tipo']; // 'up' o 'down'
    
    // Verificar si el usuario ya votó
    // (Aquí podrías implementar lógica para evitar múltiples votos)
    
    try {
        $columna = $tipo === 'up' ? 'votos_positivos' : 'votos_negativos';
        $stmt = $db->prepare("UPDATE testimonios SET $columna = $columna + 1 WHERE id = ?");
        $stmt->execute([$testimonio_id]);
        
        // Obtener el nuevo conteo
        $stmt = $db->prepare("SELECT votos_positivos, votos_negativos FROM testimonios WHERE id = ?");
        $stmt->execute([$testimonio_id]);
        $testimonio = $stmt->fetch();
        
        echo json_encode([
            'success' => true,
            'newCount' => $tipo === 'up' ? $testimonio['votos_positivos'] : $testimonio['votos_negativos']
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al procesar tu voto']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>