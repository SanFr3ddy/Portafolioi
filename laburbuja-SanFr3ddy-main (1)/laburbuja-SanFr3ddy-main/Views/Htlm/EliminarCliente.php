<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php'; // Asegúrate de que la ruta sea correcta
require_admin(); // Verifica que el usuario sea administrador

if (isset($_GET['id'])) {
    $id_cliente = intval($_GET['id']);

    // Primero, elimina las órdenes relacionadas
    $sql = "DELETE o FROM ordenes o
            WHERE o.cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();

    // Luego, elimina las ventas relacionadas
    $sql = "DELETE FROM ventas WHERE orden_id IN (SELECT id FROM ordenes WHERE cliente_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();

    // Finalmente, elimina el cliente
    $sql = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cliente, ventas, órdenes y pendientes eliminados correctamente";
    } else {
        $_SESSION['error'] = "Error al eliminar el cliente: " . $stmt->error;
    }

    header("Location: Usuarios.php");
    exit();
} else {
    $_SESSION['error'] = "ID de cliente no proporcionado";
    header("Location: Usuarios.php");
    exit();
}
?>