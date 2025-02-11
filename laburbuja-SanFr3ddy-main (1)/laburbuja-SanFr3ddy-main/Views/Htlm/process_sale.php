<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['customerId'], $data['items'])) {
        throw new Exception('Datos inválidos');
    }

    $conn->begin_transaction();

    // Insertar la orden
    $stmt = $conn->prepare("INSERT INTO ordenes (cliente_id, fecha, total) VALUES (?, NOW(), ?)");
    $stmt->bind_param("is", $data['customerId'], $data['total']);
    if (!$stmt->execute()) {
        throw new Exception('Error al insertar la orden: ' . $stmt->error);
    }

    $ordenId = $stmt->insert_id; // Obtener el ID de la orden insertada

    // Insertar los productos en orden_detalle
    foreach ($data['items'] as $item) {
        $stmt = $conn->prepare("INSERT INTO orden_detalle (orden_id, producto_servicio_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $ordenId, $item['productId'], $item['quantity'], $item['subtotal']);
        if (!$stmt->execute()) {
            throw new Exception('Error al insertar el detalle de la orden: ' . $stmt->error);
        }
    }

    // Insertar en la tabla de ventas
    $stmt = $conn->prepare("INSERT INTO ventas (orden_id, fecha, total) VALUES (?, NOW(), ?)");
    $stmt->bind_param("is", $ordenId, $data['total']);
    if (!$stmt->execute()) {
        throw new Exception('Error al insertar en ventas: ' . $stmt->error);
    }

    $conn->commit();
    $response['success'] = true;

} catch (Exception $e) {
    $conn->rollback();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>