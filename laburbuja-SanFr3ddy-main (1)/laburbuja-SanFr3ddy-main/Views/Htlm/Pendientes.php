<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Burbuja - Pendientes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
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
                            <a class="nav-link active" href="Pendientes.php">Pendientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historial.php">Historial</a>
                        </li>
                        <?php if (is_admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="productos_servicios.php">Productos y Servicios</a>
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
        <hr class="border border-primary border-3 opacity-75">

        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h2>Servicios Pendientes</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Orden</th>
                                    <th>ID Servicio</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Definir la consulta SQL
                                $sql = "SELECT * FROM pendientes_servicios WHERE estado = 'pendiente'";

                                // Ejecutar la consulta
                                $result = $conn->query($sql);
                                if ($result === false) {
                                    echo "Error en la consulta: " . $conn->error;
                                    exit;
                                }

                                // Mostrar los resultados
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['orden_id']}</td>
                                        <td>{$row['servicio_id']}</td>
                                        <td>{$row['estado']}</td>
                                        <td>" . date('d/m/Y H:i', strtotime($row['fecha'])) . "</td>
                                        <td>
                                            <button class='btn btn-success btn-sm' onclick='marcarCompletado({$row['id']})'>Completado</button>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h2>Pendientes en Dinero</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Venta</th>
                                    <th>ID Cliente</th>
                                    <th>ID Orden</th>
                                    <th>Monto</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // Definir la consulta SQL
                                $sqlDinero = "SELECT * FROM pendientes_dinero";

                                // Ejecutar la consulta
                                $resultDinero = $conn->query($sqlDinero);
                                if ($resultDinero === false) {
                                    echo "Error en la consulta: " . $conn->error;
                                    exit;
                                }

                                // Mostrar los resultados
                                while ($row = $resultDinero->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['cliente_id']}</td>
                                        <td>{$row['orden_id']}</td>
                                        <td>{$row['monto']}</td>
                                        <td>{$row['fecha']}</td>
                                        <td>
                                            <a href='editar_pendiente_dinero.php?id={$row['id']}' class='btn btn-warning'>Editar</a>
                                            <a href='eliminar_pendiente_dinero.php?id={$row['id']}' class='btn btn-danger'>Eliminar</a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       function marcarCompletado(id) {
    if (confirm('¿Estás seguro de marcar este servicio como completado?')) {
        fetch('marcar_completado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Servicio marcado como completado.');
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert('Error al marcar como completado: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
}
    </script>
</body>

</html>