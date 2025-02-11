<?php
session_start();
require_once 'check_admin.php';
require_admin();
require_once 'db.php';

// Función para obtener datos del cliente
function getClientData($conn, $id) {
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Verificar si se recibió un ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $clienteData = getClientData($conn, $id);

    if (!$clienteData) {
        $_SESSION['error'] = "Cliente no encontrado";
        header("Location: Usuarios.php");
        exit();
    }
} else {
    $_SESSION['error'] = "ID de cliente no especificado";
    header("Location: Usuarios.php");
    exit();
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $estado = isset($_POST['estado']) ? $_POST['estado'] : 'Activo'; // Valor por defecto

    // Validaciones
    if (empty($nombre) || empty($telefono) || empty($direccion)) {
        $_SESSION['error'] = "Todos los campos son requeridos";
    } else {
        try {
            // Actualizar cliente
            $sql = "UPDATE clientes SET 
                    nombre = ?, 
                    telefono = ?, 
                    direccion = ?, 
                    estado = ?
                    WHERE id_cliente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", 
                $nombre, 
                $telefono, 
                $direccion, 
                $estado,
                $id
            );

            if ($stmt->execute()) {
                $_SESSION['success'] = "Cliente actualizado correctamente";
                header("Location: Usuarios.php"); 
                exit(); 
            } else {
                throw new Exception("Error al actualizar el cliente");
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
    <title>Editar Cliente</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="stylesheet" href="../Css/usuarios.css">
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <main>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="Inicio.php">
                    <img src="../Img/imagen_2024-08-19_080627485-removebg-preview.png" alt="" width="80px" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="Inicio.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Venta.php">Venta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Pendientes.php">Pendientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historial.php">Historial</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="productos_servicios.php">Productos y Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="Usuarios.php">Usuarios</a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger" href="logout.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <hr class="border border-primary border-3 opacity-75">

        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h2>Editar Cliente de Lavandería</h2>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <form action="EditarCliente.php?id=<?php echo $id; ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?php echo htmlspecialchars($clienteData['nombre']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono"
                                    value="<?php echo htmlspecialchars($clienteData['telefono']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                    value="<?php echo htmlspecialchars($clienteData['direccion']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado del Cliente</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="Activo" <?php echo ($clienteData['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                    <option value="Inactivo" <?php echo ($clienteData['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="Usuarios.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>