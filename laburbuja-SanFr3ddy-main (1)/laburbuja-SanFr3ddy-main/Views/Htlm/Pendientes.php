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
        <div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEstadoLabel">Modificar Estado, Método de Pago y Proceso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEstado">
                            <input type="hidden" id="ordenIdEstado" name="ordenIdEstado">
                            <div class="mb-3">
                                <label for="estadoOrden" class="form-label">Estado</label>
                                <select class="form-select" id="estadoOrden" name="estadoOrden" required>
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Pagado">Pagado</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="metodoPago" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodoPago" name="metodoPago">
                                    <option value="" disabled selected>Seleccione un método de pago</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Clip">Clip</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="procesoOrden" class="form-label">Proceso</label>
                                <select class="form-select" id="procesoOrden" name="procesoOrden">
                                    <option value="" disabled selected>Seleccione un proceso</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="terminado">Terminado</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarEstado()">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>

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
                    <h2>Órdenes Pendientes</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Pago</th>
                                    <th>Medio</th>
                                    <th>Proceso</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlPendientes = "SELECT 
        o.id AS orden_id,
        c.nombre AS cliente,
        t.nombre AS tipo,
        o.estado,
        o.metodo_pago,
        o.proceso,
        o.total
    FROM orden o
    JOIN clientes c ON o.cliente_id = c.id_cliente
    JOIN tipos_orden t ON o.tipo_id = t.id_tipo
    WHERE o.proceso = 'pendiente'";
                                $resultPendientes = $conn->query($sqlPendientes);

                                if ($resultPendientes === false) {
                                    echo "Error en la consulta: " . $conn->error;
                                    exit;
                                }

                                while ($row = $resultPendientes->fetch_assoc()) {
                                    echo "<tr>
            <td>{$row['cliente']}</td>
            <td>{$row['tipo']}</td>
            <td>{$row['estado']}</td>
            <td>{$row['metodo_pago']}</td>
            <td>{$row['proceso']}</td>
            <td>$" . number_format($row['total'], 2) . "</td>
            <td>
                <button class='btn btn-primary btn-sm' onclick='marcarTerminado({$row['orden_id']})'>Marcar como Terminado</button>
            </td>
        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabla de órdenes terminadas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h2>Órdenes Terminadas</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Pago</th>
                                    <th>Medio</th>
                                    <th>Proceso</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlTerminadas = "SELECT 
                                    o.id AS orden_id,
                                    c.nombre AS cliente,
                                    t.nombre AS tipo,
                                    o.estado,
                                    o.metodo_pago,
                                    o.proceso,
                                    o.total
                                FROM orden o
                                JOIN clientes c ON o.cliente_id = c.id_cliente
                                JOIN tipos_orden t ON o.tipo_id = t.id_tipo
                                WHERE o.proceso = 'terminado' AND o.estado != 'pagado'";
                                $resultTerminadas = $conn->query($sqlTerminadas);

                                if ($resultTerminadas === false) {
                                    echo "Error en la consulta: " . $conn->error;
                                    exit;
                                }

                                while ($row = $resultTerminadas->fetch_assoc()) {
                                    echo "<tr>
                            <td>{$row['cliente']}</td>
                            <td>{$row['tipo']}</td>
                            <td>{$row['estado']}</td>
                            <td>{$row['metodo_pago']}</td>
                            <td>{$row['proceso']}</td>
                            <td>$" . number_format($row['total'], 2) . "</td>
                            <td>
                                <button class='btn btn-success btn-sm' onclick='abrirModalEstado({$row["orden_id"]}, \"{$row["estado"]}\")'>Pagar</button>
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
        function marcarPagado(id) {
            if (confirm('¿Estás seguro de marcar esta orden como pagada?')) {
                fetch('actualizar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            estado: 'pagado'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Orden marcada como pagada.');
                            location.reload(); // Recargar la página para ver los cambios
                        } else {
                            alert('Error al marcar como pagada: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
            }
        }

        function marcarTerminado(id) {
            if (confirm('¿Estás seguro de marcar esta orden como terminada?')) {
                fetch('actualizar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            proceso: 'terminado' // Solo se actualiza el proceso
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Orden marcada como terminada.');
                            location.reload(); // Recargar la página para ver los cambios
                        } else {
                            alert('Error al marcar como terminada: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
            }
        }
    </script>
    <!-- modal de pago -->
    <script>
        function abrirModalEstado(id, estadoActual, metodoPagoActual) {
            document.getElementById('ordenIdEstado').value = id; // Establecer el ID de la orden en el modal
            document.getElementById('estadoOrden').value = estadoActual; // Establecer el estado actual
            document.getElementById('metodoPago').value = metodoPagoActual; // Establecer el método de pago actual
            const modal = new bootstrap.Modal(document.getElementById('modalEstado'));
            modal.show();
        }

        function guardarEstado() {
            const id = document.getElementById('ordenIdEstado').value;
            const nuevoEstado = document.getElementById('estadoOrden').value;
            const nuevoMetodoPago = document.getElementById('metodoPago').value;
            const nuevoProceso = document.getElementById('procesoOrden').value;

            if (!nuevoEstado) {
                alert('Por favor, seleccione un estado.');
                return;
            }

            fetch('actualizar_estado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        estado: nuevoEstado,
                        metodo_pago: nuevoMetodoPago,
                        proceso: nuevoProceso
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Estado, método de pago y proceso actualizados correctamente.');
                        location.reload(); // Recargar la página para ver los cambios
                    } else {
                        alert('Error al actualizar: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud');
                });
        }
    </script>
</body>

</html>