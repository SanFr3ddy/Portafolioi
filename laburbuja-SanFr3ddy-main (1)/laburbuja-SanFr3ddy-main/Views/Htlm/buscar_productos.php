<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'data' => [], 'error' => null];

try {
    if (isset($_GET['q'])) {
        $search = '%' . $_GET['q'] . '%';
        
        // Para depuración
        error_log("Búsqueda recibida: " . $_GET['q']);
        
        $stmt = $conn->prepare("SELECT id, nombre, precio FROM productos_servicios WHERE nombre LIKE ?");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
        
        $stmt->bind_param("s", $search);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'precio' => floatval($row['precio'])
            ];
        }
        
        $response['success'] = true;
        
        // Para depuración
        error_log("Resultados encontrados: " . count($response['data']));
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log("Error en buscar_productos.php: " . $e->getMessage());
}

echo json_encode($response);