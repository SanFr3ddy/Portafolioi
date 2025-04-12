<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php';

require_admin();

// Lógica para agregar o modificar productos/servicios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $tipo_id = intval($_POST['tipo_id']);
    $precio = floatval($_POST['precio']);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Modificar producto/servicio existente
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE productos_servicios SET nombre = ?, tipo_id = ?, precio = ? WHERE id = ?");
        $stmt->bind_param("sidi", $nombre, $tipo_id, $precio, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Producto/servicio actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el producto/servicio";
        }
    } else {
        // Agregar nuevo producto/servicio
        $stmt = $conn->prepare("INSERT INTO productos_servicios (nombre, tipo_id, precio) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $nombre, $tipo_id, $precio);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Producto/servicio agregado correctamente";
        } else {
            $_SESSION['error'] = "Error al agregar el producto/servicio";
        }
    }
    header("Location: productos_servicios.php");
    exit();
}

// Lógica para eliminar producto/servicio
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM productos_servicios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Producto/servicio eliminado correctamente";
    } else {
        $_SESSION['error'] = "Error al eliminar el producto/servicio";
    }
    header("Location: productos_servicios.php");
    exit();
}

// Definir la variable $search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener productos/servicios
$sql = "SELECT productos_servicios.id, productos_servicios.nombre, productos_servicios.precio, productos_servicios.tipo_id, tipos_orden.nombre AS tipo
        FROM productos_servicios
        JOIN tipos_orden ON productos_servicios.tipo_id = tipos_orden.id_tipo
        WHERE productos_servicios.nombre LIKE ?
        ORDER BY productos_servicios.nombre ASC";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos y Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <main>
        <!-- Navbar -->
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
                        <?php if (is_admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="productos_servicios.php">Productos y Servicios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="Usuarios.php">Usuarios</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger" href="logout.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- linea -->
        <hr class="border border-primary border-3 opacity-75">
        <?php   
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $_SESSION['success'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $_SESSION['error'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['error']);
            }
            ?>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h2>Productos y Servicios</h2>
                </div>
                <div class="card-body">
                    <!-- Formulario de búsqueda -->
                    <form action="" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar producto o servicio" name="search" value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </form>
                    <!-- Formulario para agregar o modificar productos -->
                    <form action="" method="POST" class="mb-4">
                        <input type="hidden" name="id" id="id">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del producto" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="tipo_id" id="tipo_id" required>
                                    <option value="" disabled selected>Seleccionar tipo</option>
                                    <?php
                                    $tipos = $conn->query("SELECT id_tipo, nombre FROM tipos_orden");
                                    while ($tipo = $tipos->fetch_assoc()) {
                                        echo "<option value='{$tipo['id_tipo']}'>{$tipo['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="precio" id="precio" placeholder="Precio" step="0.01" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="add" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-lg"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de productos y servicios -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editarProducto(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nombre']); ?>', <?php echo $row['tipo_id']; ?>, <?php echo $row['precio']; ?>)">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <a href="productos_servicios.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formId').addEventListener('submit', function(event) {
            if (!validarDatos()) {
                event.preventDefault(); // Prevenir el envío del formulario
                alert('Por favor complete todos los campos.');
            }
        });

        function editarProducto(id, nombre, tipo_id, precio) {
            document.getElementById('id').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('tipo_id').value = tipo_id;
            document.getElementById('precio').value = precio;
        }
    </script>
</body>

</html>