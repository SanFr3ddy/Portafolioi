<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Método no permitido.');
    }

    $term = isset($_GET['term']) ? trim($_GET['term']) : '';

    if (empty($term)) {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id_cliente, nombre, telefono, direccion FROM clientes_listado WHERE nombre LIKE ? AND estado != 'inactivo'");
    $searchTerm = "%$term%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    echo json_encode($clientes);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>