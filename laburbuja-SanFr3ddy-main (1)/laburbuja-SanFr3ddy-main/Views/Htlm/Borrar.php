<?php
session_start();
require_once 'check_admin.php';
require_admin();
require_once 'db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM usuarios_listado WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuario eliminado correctamente";
        } else {
            throw new Exception("Error al eliminar el usuario");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: Usuarios.php");
    exit();
} else {
    $_SESSION['error'] = "ID de usuario no especificado";
    header("Location: Usuarios.php");
    exit();
}
?>