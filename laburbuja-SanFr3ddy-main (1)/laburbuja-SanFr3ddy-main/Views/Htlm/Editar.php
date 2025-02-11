<?php
session_start();
require_once 'check_admin.php';
require_admin();
require_once 'db.php';

// Función para obtener datos del usuario
function getUserData($conn, $id) {
    $sql = "SELECT * FROM usuarios_listado WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Verificar si se recibió un ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $userData = getUserData($conn, $id);

    if (!$userData) {
        $_SESSION['error'] = "Usuario no encontrado";
        header("Location: Usuarios.php");
        exit();
    }
} else {
    $_SESSION['error'] = "ID de usuario no especificado";
    header("Location: Usuarios.php");
    exit();
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $new_password = trim($_POST['new_password']);
    $rol = trim($_POST['rol']);

    // Validaciones
    if (empty($nombre) || empty($correo)) {
        $_SESSION['error'] = "Nombre y correo son requeridos";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de correo electrónico inválido";
    } else {
        try {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios_listado SET nombre = ?, correo = ?, password = ?, rol = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $nombre, $correo, $hashed_password, $rol, $id);
            } else {
                $sql = "UPDATE usuarios_listado SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
            }

            if ($stmt->execute()) {
                $_SESSION['success'] = "Usuario actualizado correctamente";
                header("Location: Usuarios.php");
                exit();
            } else {
                throw new Exception("Error al actualizar el usuario");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Burbuja</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
    <link rel="stylesheet" href="../Css/usuarios.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="Editar.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($userData['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($userData['correo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-control" id="rol" name="rol">
                    <option value="usuario" <?php echo ($userData['rol'] == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo ($userData['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="Usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>