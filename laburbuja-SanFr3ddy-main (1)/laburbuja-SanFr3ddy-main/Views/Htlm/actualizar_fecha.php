<?php
require_once 'db.php';
header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['fecha'])) {
        throw new Exception('Datos incompletos');
    }

    $stmt = $conn->prepare("UPDATE pendientes SET fecha_entrega = ? WHERE id_venta = ?");
    $stmt->bind_param("si", $data['fecha'], $data['id']);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        throw new Exception('Error al actualizar la fecha');
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);