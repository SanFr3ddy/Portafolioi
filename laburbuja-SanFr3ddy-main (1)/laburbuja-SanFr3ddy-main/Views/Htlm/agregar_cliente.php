<?php
session_start();
include 'db.php';

// Verificar si se enviaron todos los campos
if (isset($_POST['customerName'], $_POST['customerPhone'], $_POST['customerAddress'], $_POST['customerState']) &&
    !empty($_POST['customerName']) &&
    !empty($_POST['customerPhone']) &&
    !empty($_POST['customerAddress']) &&
    !empty($_POST['customerState'])) {

    $nombre = trim($_POST['customerName']);
    $telefono = trim($_POST['customerPhone']);
    $direccion = trim($_POST['customerAddress']);
    $estado = trim($_POST['customerState']); // Nuevo campo para el estado

    // Insertar el nuevo cliente en la base de datos
    $sql = "INSERT INTO clientes (nombre, telefono, direccion, estado) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $telefono, $direccion, $estado);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente agregado correctamente.";
    } else {
        $_SESSION['error'] = "Error al agregar el cliente: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Todos los campos son requeridos.";
}

$conn->close();

// Redirigir de vuelta a la página de ventas
header("Location: Venta.php");
exit();
?>