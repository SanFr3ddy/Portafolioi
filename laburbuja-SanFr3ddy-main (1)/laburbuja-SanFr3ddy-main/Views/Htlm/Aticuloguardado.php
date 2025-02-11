<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        $response['error'] = 'Datos inválidos';
        echo json_encode($response);
        exit;
    }

    $saleData = $data['saleData'];
    $customerName = $data['customerName'];
    $isPaid = $data['isPaid'];

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Insertar o actualizar cliente
        $stmt = $conn->prepare("INSERT INTO clientes (nombre) VALUES (?) ON DUPLICATE KEY UPDATE id_cliente = LAST_INSERT_ID(id_cliente)");
        $stmt->bind_param("s", $customerName);
        $stmt->execute();
        $clienteId = $stmt->insert_id;

        // Insertar venta
        $stmt = $conn->prepare("INSERT INTO ventas (id_cliente, fecha, estado_pago) VALUES (?, NOW(), ?)");
        $estadoPago = $isPaid ? 'pagado' : 'pendiente';
        $stmt->bind_param("is", $clienteId, $estadoPago);
        $stmt->execute();
        $ventaId = $stmt->insert_id;

        // Insertar detalles de venta y pendientes
        $stmt = $conn->prepare("INSERT INTO detalles_venta (id_venta, producto, cantidad, precio) VALUES (?, ?, ?, ?)");
        $stmtPendientes = $conn->prepare("INSERT INTO pendientes (id_venta, servicio, fecha_entrega) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 2 DAY))");

        foreach ($saleData as $item) {
            $stmt->bind_param("isid", $ventaId, $item['nombre'], $item['cantidad'], $item['precio']);
            $stmt->execute();

            // Si es un servicio, añadirlo a pendientes
            if (in_array($item['nombre'], ['Servicio Completo', 'Lavadora', 'Secadora', 'Plancha', 'Tintorería'])) {
                $stmtPendientes->bind_param("is", $ventaId, $item['nombre']);
                $stmtPendientes->execute();
            }
        }

        // Confirmar transacción
        $conn->commit();
        $response['success'] = true;

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollback();
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);