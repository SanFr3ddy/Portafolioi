<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php';
$sql = "SELECT v.id AS id_venta, c.nombre AS cliente, v.fecha, v.total 
        FROM ventas v
        JOIN ordenes o ON v.orden_id = o.id
        JOIN clientes c ON o.cliente_id = c.id_cliente
        ORDER BY v.fecha";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
</head>

<body>
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
                        <a class="nav-link active" href="historial.php">Historial</a>
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
                <h2>Historial de Ventas</h2>
                <button id="corteDiaBtn" class="btn btn-primary">
                    <i class="bi bi-download"></i> Realizar Corte del Día
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare($sql);
                            if ($stmt === false) {
                                die("Error en la preparación de la consulta: " . $conn->error);
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["id_venta"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["cliente"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["fecha"]) . "</td>";
                                    echo "<td>$" . number_format($row["total"], 2) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No hay ventas registradas</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script>
        document.getElementById('corteDiaBtn').addEventListener('click', function () {
            if (confirm('¿Está seguro de realizar el corte del día? Esta acción moverá todas las ventas del día al historial.')) {
                fetch('corte_dia.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Corte del día realizado con éxito. El archivo se ha guardado como: ' + data.filename);
                            // Descargar el archivo
                            window.location.href = '../Cortes/' + data.filename;
                            // Recargar la página después de un breve delay
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            alert('Error al realizar el corte: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
            }
        });
        $.ajax({
            url: 'corte_dia.php',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Redirigir a la página de historial
                    window.location.href = 'historial.php'; // Cambia esto a la URL de tu página de historial
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function (xhr, status, error) {
                alert('Error al procesar la solicitud: ' + error);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>