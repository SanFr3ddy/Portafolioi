<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

try {
    // Obtener las órdenes pendientes con nombres de cliente y tipo
    $sqlPendientes = "SELECT 
                        o.id, 
                        c.nombre AS cliente, 
                        t.nombre AS tipo, 
                        o.estado, 
                        o.proceso, 
                        o.total, 
                        o.fecha_creacion 
                      FROM orden o
                      JOIN clientes c ON o.cliente_id = c.id_cliente
                      JOIN tipos_orden t ON o.tipo_id = t.id_tipo
                      WHERE o.proceso = 'pendiente'";
    $resultPendientes = $conn->query($sqlPendientes);

    // Obtener las órdenes pagadas con nombres de cliente y tipo
    $sqlPagadas = "SELECT 
                    o.id, 
                    c.nombre AS cliente, 
                    t.nombre AS tipo, 
                    o.estado, 
                    o.proceso, 
                    o.total, 
                    o.fecha_creacion 
                   FROM orden o
                   JOIN clientes c ON o.cliente_id = c.id_cliente
                   JOIN tipos_orden t ON o.tipo_id = t.id_tipo
                   WHERE o.estado = 'pagado'";
    $resultPagadas = $conn->query($sqlPagadas);

    // Calcular el total de pagos
    $sqlTotalPagos = "SELECT SUM(total) AS total_pagos FROM orden WHERE estado = 'pagado'";
    $resultTotalPagos = $conn->query($sqlTotalPagos);
    $totalPagos = $resultTotalPagos->fetch_assoc()['total_pagos'];

    // Crear una carpeta para el mes actual
    $mesActual = date('F_Y'); // Ejemplo: "April_2025"
    $carpeta = "../Cortes/$mesActual";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    // Crear el contenido del archivo
    $contenido = "Corte del Día - " . date('Y-m-d') . "\n\n";

    // Encabezado de la tabla de órdenes pendientes
    $contenido .= "Órdenes Pendientes:\n";
    $contenido .= sprintf("%-5s %-20s %-20s %-10s %-10s %-10s %-20s\n", "ID", "Cliente", "Tipo", "Estado", "Proceso", "Total", "Fecha");
    $contenido .= str_repeat("-", 95) . "\n";

    while ($row = $resultPendientes->fetch_assoc()) {
        $contenido .= sprintf(
            "%-5s %-20s %-20s %-10s %-10s $%-9.2f %-20s\n",
            $row['id'],
            substr($row['cliente'], 0, 20), // Limitar el nombre del cliente a 20 caracteres
            substr($row['tipo'], 0, 20),    // Limitar el tipo a 20 caracteres
            $row['estado'],
            $row['proceso'],
            $row['total'],
            $row['fecha_creacion']
        );
    }

    $contenido .= "\nÓrdenes Pagadas:\n";
    $contenido .= sprintf("%-5s %-20s %-20s %-10s %-10s %-10s %-20s\n", "ID", "Cliente", "Tipo", "Estado", "Proceso", "Total", "Fecha");
    $contenido .= str_repeat("-", 95) . "\n";

    while ($row = $resultPagadas->fetch_assoc()) {
        $contenido .= sprintf(
            "%-5s %-20s %-20s %-10s %-10s $%-9.2f %-20s\n",
            $row['id'],
            substr($row['cliente'], 0, 20), // Limitar el nombre del cliente a 20 caracteres
            substr($row['tipo'], 0, 20),    // Limitar el tipo a 20 caracteres
            $row['estado'],
            $row['proceso'],
            $row['total'],
            $row['fecha_creacion']
        );
    }

    $contenido .= "\nTotal de Pagos: $" . number_format($totalPagos, 2) . "\n";

    // Guardar el archivo en la carpeta del mes actual
    $nombreArchivo = 'Corte_' . date('Y-m-d_H-i-s') . '.txt';
    $rutaArchivo = $carpeta . '/' . $nombreArchivo;

    file_put_contents($rutaArchivo, $contenido);

    // Responder con el nombre del archivo generado
    echo json_encode(['success' => true, 'filename' => "$mesActual/$nombreArchivo"]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>