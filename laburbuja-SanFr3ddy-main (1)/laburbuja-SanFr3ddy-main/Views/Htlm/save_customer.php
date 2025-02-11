<?php
session_start();
require_once 'db.php';

$response = ['success' => false, 'error' => '', 'customerId' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $customerId = intval($_POST['customerId'] ?? 0);
    $name = trim($_POST['customerName'] ?? '');
    $phone = trim($_POST['customerPhone'] ?? '');
    $address = trim($_POST['customerAddress'] ?? ''); // Asegúrate de que sea customerAddress

    // Validaciones
    if (empty($name)) {
        $response['error'] = 'El nombre del cliente es requerido';
    } elseif (empty($address)) {
        $response['error'] = 'Usuarios.php'; // Validación de dirección
    } else {
        // Verificar si el cliente ya existe
        $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE id_cliente = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar cliente existente
            $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE id_cliente = ?");
            $stmt->bind_param("sssi", $name, $phone, $address, $customerId);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['customerId'] = $customerId;
            } else {
                $response['error'] = 'Error al actualizar el cliente: ' . $stmt->error;
            }
        } else {
            $response['error'] = 'El cliente no existe.';
        }
    }
}

echo json_encode($response);