<?php
// corte_dia.php
require_once 'db.php';
session_start();

header('Content-Type: application/json');
$response = ['success' => false, 'error' => ''];

try {
    // Consulta para obtener todas las ventas del día
    $sql = "SELECT v.id, v.fecha, v.total FROM ventas v WHERE DATE(v.fecha) = CURDATE()";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }

    // Verifica si hay ventas
    if ($result->num_rows > 0) {
        // Generar el contenido del archivo
        $contenido = "CORTE DE CAJA - LAVANDERÍA LA BURBUJA\n";
        $contenido .= "Fecha de corte: " . date('Y-m-d H:i:s') . "\n";
        $contenido .= "----------------------------------------\n";

        while ($row = $result->fetch_assoc()) {
            $contenido .= sprintf("ID: %d, Fecha: %s, Total: $%.2f\n", $row['id'], $row['fecha'], $row['total']);
        }

        // Guardar el archivo
        $nombre_archivo = 'corte_' . date('Ymd_His') . '.txt';
        file_put_contents('../Cortes/' . $nombre_archivo, $contenido);

        // Limpiar la tabla de ventas
        $conn->query("DELETE FROM ventas WHERE DATE(fecha) = CURDATE()");

        $response['success'] = true;
        $response['filename'] = $nombre_archivo;
    } else {
        $response['error'] = 'No hay ventas para procesar.';
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>