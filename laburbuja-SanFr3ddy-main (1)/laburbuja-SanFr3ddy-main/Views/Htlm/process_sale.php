<?php
require_once 'db.php';
file_put_contents('log.txt', print_r($data, true));
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Registrar los datos recibidos para depuración
    file_put_contents('log.txt', print_r($data, true));

    if (!isset($data['customerId'], $data['items'], $data['estado'], $data['metodo_pago'])) {
        throw new Exception('Datos incompletos.');
    }

    $customerId = intval($data['customerId']);
    $estado = $data['estado'];
    $metodo_pago = $data['metodo_pago'];
    $items = $data['items'];

    // Insertar la orden en la base de datos
    $stmt = $conn->prepare("INSERT INTO orden (cliente_id, total, estado, metodo_pago, fecha_creacion) VALUES (?, ?, ?, ?, NOW())");
    $total = array_reduce($items, function ($sum, $item) {
        return $sum + ($item['precio'] * $item['cantidad']);
    }, 0);
    $stmt->bind_param("idss", $customerId, $total, $estado, $metodo_pago);

    if (!$stmt->execute()) {
        throw new Exception('Error al guardar la orden: ' . $stmt->error);
    }

    $orderId = $stmt->insert_id;

    // Insertar los detalles de la orden
    $stmt = $conn->prepare("INSERT INTO detalle_orden (orden_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt->bind_param("iiid", $orderId, $item['id'], $item['cantidad'], $item['precio']);
        if (!$stmt->execute()) {
            throw new Exception('Error al guardar los detalles de la orden: ' . $stmt->error);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>