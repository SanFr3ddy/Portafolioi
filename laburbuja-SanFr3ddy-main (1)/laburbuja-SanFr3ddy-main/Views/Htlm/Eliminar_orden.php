<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $ordenId = intval($data['id']);

    if (!$ordenId) {
        throw new Exception('ID de orden no proporcionado.');
    }

    // Eliminar la orden de la base de datos
    $stmt = $conn->prepare("DELETE FROM orden WHERE id = ?");
    $stmt->bind_param("i", $ordenId);

    if (!$stmt->execute()) {
        throw new Exception('Error al eliminar la orden: ' . $stmt->error);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>