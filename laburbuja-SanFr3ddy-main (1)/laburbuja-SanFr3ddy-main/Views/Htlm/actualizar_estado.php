<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    // Obtener los datos enviados desde el cliente
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['id'])) {
        throw new Exception('Datos incompletos.');
    }

    $id = intval($data['id']);
    $estado = isset($data['estado']) ? $data['estado'] : null;
    $proceso = isset($data['proceso']) ? $data['proceso'] : null;

    // Validar que al menos uno de los campos (estado o proceso) esté presente
    if (!$estado && !$proceso) {
        throw new Exception('No se especificó ningún cambio.');
    }

    // Construir la consulta SQL dinámicamente
    $sql = "UPDATE orden SET ";
    $params = [];
    $types = "";

    if ($estado) {
        $sql .= "estado = ?, ";
        $params[] = $estado;
        $types .= "s";
    }

    if ($proceso) {
        $sql .= "proceso = ?, ";
        $params[] = $proceso;
        $types .= "s";
    }

    // Eliminar la última coma y agregar la cláusula WHERE
    $sql = rtrim($sql, ", ") . " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
    }

    echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>