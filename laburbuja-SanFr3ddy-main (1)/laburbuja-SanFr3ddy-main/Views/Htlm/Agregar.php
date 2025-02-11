<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son requeridos";
        header("Location: Usuarios.php");
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de correo electrónico inválido";
        header("Location: Usuarios.php");
        exit();
    }

    try {
        // Verificar si el correo ya existe
        $check_sql = "SELECT id FROM usuarios_listado WHERE correo = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $correo);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "El correo electrónico ya está registrado";
            header("Location: Usuarios.php");
            exit();
        }

        // Encriptar contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios_listado (nombre, correo, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta");
        }
        
        $stmt->bind_param("sss", $nombre, $correo, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuario agregado correctamente";
        } else {
            throw new Exception("Error al agregar el usuario");
        }

        $stmt->close();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: Usuarios.php");
    exit();
}

header("Location: Usuarios.php");
exit();
?>