<?php
require_once 'db.php';
require_once 'check_admin.php';

function cargar_clientes($conn, $searchTerm = '')
{
    $sql = "SELECT * FROM clientes WHERE nombre LIKE ? ORDER BY id_cliente DESC";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchTerm%"; // Agregar comodines para LIKE
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error']);
}
// Obtener el término de búsqueda si está presente
$search = isset($_GET['search']) ? $_GET['search'] : '';
$clientes = cargar_clientes($conn, $search);

?>
<!doctype html>
<html lang="es">

<head>
    <title>Burbuja - Usuarios</title>
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
        <!-- linea -->
        <hr class="border border-primary border-3 opacity-75">

        <div class="container mt-4">
            <!-- Mensajes de notificación -->
            <?php
            session_start();
            require_once 'check_admin.php';
            require_admin();

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

            <div class="card">
                <div class="card-header">
                    <h1>Agregar Usuario</h1>
                </div>
                <div class="card-body">
                    <form action="Agregar.php" method="POST" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" name="nombre" class="form-control" id="floatingNombre"
                                        placeholder="Nombre" required>
                                    <label for="floatingNombre">Nombre</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="email" name="correo" class="form-control" id="floatingEmail"
                                        placeholder="Correo" required>
                                    <label for="floatingEmail">Correo</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" id="floatingPassword"
                                        placeholder="Contraseña" required>
                                    <label for="floatingPassword">Contraseña</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100 h-100">
                                    <i class="bi bi-person-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de usuarios existentes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h1>Usuarios</h1>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db.php';
                            $sql = "SELECT * FROM usuarios_listado ORDER BY id DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["nombre"] . "</td>";
                                    echo "<td>" . $row["correo"] . "</td>";
                                    echo "<td>" . ucfirst($row["rol"]) . "</td>";
                                    echo "<td>" . $row["fecha_registro"] . "</td>";
                                    echo "<td>
                    <a href='Editar.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm'>
                        <i class='bi bi-pencil'></i>
                    </a>
                    <a href='Borrar.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>
                        <i class='bi bi-trash'></i>
                    </a>
                  </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No hay usuarios registrados</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h2>Clientes</h2>
                </div>
                <form method="GET" action="" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Buscar cliente..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo $cliente['id_cliente']; ?></td>
                                    <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                                    <td>
                                        <a href="EditarCliente.php?id=<?php echo $cliente['id_cliente']; ?>"
                                            class="btn btn-warning btn-sm">Editar</a>
                                        <a href="EliminarCliente.php?id=<?php echo $cliente['id_cliente']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#clientesTable tbody tr');

            tableRows.forEach(row => {
                const nombreCell = row.cells[1].textContent.toLowerCase();
                if (nombreCell.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>