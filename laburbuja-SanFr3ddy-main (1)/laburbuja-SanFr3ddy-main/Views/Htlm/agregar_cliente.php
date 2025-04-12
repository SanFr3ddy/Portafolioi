<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        throw new Exception('No se recibieron datos.');
    }

    $nombre = trim($data['customerName']);
    $telefono = trim($data['customerPhone']);
    $direccion = trim($data['customerAddress']);

    if (empty($nombre) || empty($telefono) || empty($direccion)) {
        throw new Exception('Todos los campos son obligatorios.');
    }

    // Verificar si el cliente ya existe
    $stmt = $conn->prepare("SELECT id_cliente FROM clientes_listado WHERE nombre = ? AND telefono = ?");
    $stmt->bind_param("ss", $nombre, $telefono);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception('El cliente ya existe.');
    }

    // Insertar nuevo cliente
    $stmt = $conn->prepare("INSERT INTO clientes_listado (nombre, telefono, direccion, estado) VALUES (?, ?, ?, 'activo')");
    if (!$stmt) {
        throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
    }
    $stmt->bind_param("sss", $nombre, $telefono, $direccion);

    if (!$stmt->execute()) {
        throw new Exception('Error al guardar el cliente: ' . $stmt->error);
    }

    echo json_encode(['success' => true, 'message' => 'Cliente guardado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>