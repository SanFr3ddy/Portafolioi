<?php
require_once 'db.php';

$term = $_GET['term'] ?? '';

if (strlen($term) > 2) {
    $stmt = $conn->prepare("SELECT id_cliente, nombre, telefono, direccion 
                            FROM clientes 
                            WHERE nombre LIKE ? 
                            AND estado = 'Activo' 
                            LIMIT 5");
    $searchTerm = "%$term%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }

    echo json_encode($customers);
} 