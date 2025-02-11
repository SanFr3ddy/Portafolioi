<?php
require_once 'db.php';
header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_venta = intval($_POST['id']);

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Obtener datos de la venta pendiente
        $stmt = $conn->prepare("SELECT * FROM pendientes WHERE id_venta = ?");
        $stmt->bind_param("i", $id_venta);
        $stmt->execute();
        $pendiente = $stmt->get_result()->fetch_assoc();

        if ($pendiente) {
            // Mover datos a la tabla de órdenes
            $stmt = $conn->prepare("INSERT INTO orden (id_venta, servicio, detalles, estado, fecha_entrega, fecha_ingreso ) VALUES (?, ?, ?, 'terminado',?,?)");
            $stmt->bind_param("iiss", $pendiente['id_venta'],  $pendiente['servicio'], $pendiente['detalles'], $pendiente['estado'], $pendiente['fecha_entrega'], $pendiente['fecha_ingreso']);
            $stmt->execute();

            // Eliminar de la tabla de pendientes
            $stmt = $conn->prepare("DELETE FROM pendientes WHERE id_venta = ?");
            $stmt->bind_param("i", $id_venta);
            $stmt->execute();

            // Confirmar transacción
            $conn->commit();
            $response['success'] = true;
        } else {
            $response['error'] = 'Pendiente no encontrado';
        }
    } catch (Exception $e) {
        $conn->rollback();
        $response['error'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['error'] = 'ID no proporcionado';
}

echo json_encode($response);