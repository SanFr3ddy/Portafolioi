<?php
require_once 'db.php';
header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id']);

    try {
        $stmt = $conn->prepare("UPDATE pendientes_servicios SET estado = 'completado' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            throw new Exception('Error al actualizar el estado del servicio');
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
?>