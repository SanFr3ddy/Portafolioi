<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: historial.php");
    exit();
}

$id_venta = intval($_GET['id']);

$sql = "SELECT v.*, c.nombre as cliente, dv.producto, dv.cantidad, dv.precio
        FROM ventas v
        JOIN clientes c ON v.id_cliente = c.id_cliente
        JOIN detalles_venta dv ON v.id_venta = dv.id_venta
        WHERE v.id_venta = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/General.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="Inicio.php">
                <img src="../Img/imagen_2024-08-19_080627485-removebg-preview.png" alt="" width="80px" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <h2>Detalles de Venta #<?php echo $id_venta; ?></h2>
            </div>
            <div class="card-body">
                <?php
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p><strong>Cliente:</strong> " . htmlspecialchars($row['cliente']) . "</p>";
                    echo "<p><strong>Fecha:</strong> " . htmlspecialchars($row['fecha']) . "</p>";
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                do {
                                    $subtotal = $row['cantidad'] * $row['precio'];
                                    $total += $subtotal;
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['producto']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                                    echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                                    echo "<td>$" . number_format($subtotal, 2) . "</td>";
                                    echo "</tr>";
                                } while ($row = $result->fetch_assoc());
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th><?php echo "$" . number_format($total, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="alert alert-warning">No se encontraron detalles para esta venta.</p>
                <?php } ?>
                
                <div class="mt-3">
                    <a href="historial.php" class="btn btn-primary">Volver al Historial</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>