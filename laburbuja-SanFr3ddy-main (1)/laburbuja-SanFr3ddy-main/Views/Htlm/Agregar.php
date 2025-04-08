<?php
session_start();
include 'db.php';

// Verificar si se enviaron todos los campos
if (isset($_POST['nombre'], $_POST['correo'], $_POST['contrasena']) && 
    !empty($_POST['nombre']) && 
    !empty($_POST['correo']) && 
    !empty($_POST['contrasena'])) {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Encriptar la contraseña antes de almacenarla
    $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios_listado (nombre, correo, contraseña, rol) VALUES (?, ?, ?, 'Usuario')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $correo, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuario agregado correctamente.";
    } else {
        $_SESSION['error'] = "Error al agregar el usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Todos los campos son requeridos.";
}

$conn->close();

// Redirigir de vuelta a la página de usuarios
header("Location: Usuarios.php");
exit();
?>