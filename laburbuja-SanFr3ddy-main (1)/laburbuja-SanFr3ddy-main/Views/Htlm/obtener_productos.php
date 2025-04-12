<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id, nombre, precio FROM productos_servicios");
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>