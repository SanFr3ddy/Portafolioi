<?php
session_start();
require_once 'db.php';
require_once 'check_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="es">

<head>
    <title>Burbuja</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
    <link rel="stylesheet" href="../Css/General.css">
    <link rel="stylesheet" href="../Css/Carrito.css">
    <!-- Emoticonos -->
    <link href="/your-path-to-uicons/css/uicons-rounded-regular.css" rel="stylesheet">
    <link href="/your-path-to-uicons/css/uicons-rounded-bold.css" rel="stylesheet">
    <link href="/your-path-to-uicons/css/uicons-rounded-solid.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <main>
        <!-- MODALES -->
        <div class="modal fade" id="Servicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Servicio Completo</h5>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <img src="https://img.freepik.com/foto-gratis/polo-moda-hombre_74190-4858.jpg?t=st=1727670448~exp=1727674048~hmac=9672b83e072dfdff34a657f89e6b81c9a5ee48c82469e4cf705327a677b8275d&w=1380"
                                    alt="" width="50%" class="imgcarta">
                                <p><strong>Incluye:</strong> Lavado, Secado y Doblado de la ropa proporcionada.
                                    Este servicio se cobra mediante el peso estimado de la ropa, con un costo de
                                    <strong>$30</strong> por kilo.
                                </p>
                            </div>
                            <div class="right_modal">
                                <form id="form-servicio-completo">
                                    <input type="hidden" id="servicio-id" value="1"> <!-- ID del servicio completo -->
                                    <input type="hidden" id="servicio-precio" value="30">
                                    <div class="mb-3">
                                        <label for="kilos" class="form-label">Kilos de ropa</label>
                                        <input type="number" class="form-control" id="kilos" min="0.5" step="0.5"
                                            value="1">
                                    </div>
                                    <div class="mb-3">
                                        <h4>Total: $<span id="total-servicio">30.00</span></h4>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar" id="agregar-servicio-carrito">
                            <i class="bi bi-plus"></i>Agregar
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal lavadora -->
        <div class="modal fade" id="Lavadora" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Lavadora"></h1>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <img src="https://img.freepik.com/foto-gratis/mujer-usando-capsula-lavar-su-ropa_23-2149117042.jpg?t=st=1727671748~exp=1727675348~hmac=4db3612e611fa82cb8bb656ff40f7ac622e01533622bf284dd38b1791a5951dd&w=1380"
                                    alt="" width="100%" class="imgcarta">
                                <p><strong>Incluye:</strong> Lavado de ropa dependiendo el tamaño
                                    y la cantidad de ropa, se le cobrara <strong>solamente el tipo de lavadora</strong>
                                </p>
                            </div>
                            <div class="right_modal">
                                <div id="cantidad">
                                    <div>
                                        <form id="form-">
                                            <h5>CANTIDAD</h5>
                                            <input value="1" type="number" step="0" id="cantidad-lavadoras">
                                            <h5>LAVADORA</h5>
                                            <select id="tipo-lavadora">
                                                <option value="Lavadora Chica">Lavadora Chica</option>
                                                <option value="Lavadora Normal">Lavadora Normal</option>
                                                <option value="Lavadora Grande">Lavadora Grande</option>
                                                <option value="Lavadora Industrial">Lavadora Industrial</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <hr width="80%" style="margin: auto;">
                                <p id="costo-total-lavadora" class="precio"> TOTAL $</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar"><i class="bi bi-plus"></i>Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Secadora -->
        <div class="modal fade" id="Secadora" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Lavadora"></h1>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <img src="https://img.freepik.com/foto-gratis/vista-frontal-canasta-blanca-toallas_23-2148251849.jpg?t=st=1727672109~exp=1727675709~hmac=c0dc5f46b0fa133e723983003fe56db730cf43dc8d09dae190019c3d1f8e1791&w=1380"
                                    alt="" width="100%" class="imgcarta">
                                <p><strong>Incluye:</strong> Secado de ropa,
                                    se le cobrara solamente la cantidad de secadoras usadas.
                                </p>
                            </div>
                            <div class="right_modal">
                                <div id="cantidad">
                                    <div>
                                        <form id="form">
                                            <h5>CANTIDAD</h5>

                                            <input type="number" id="cantidad-secadora" value="0">
                                        </form>
                                    </div>
                                </div>
                                <hr width="80%" style="margin: auto;">
                                <p class="precio"> TOTAL $<span id="total-secadora">0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar"><i class="bi bi-plus"></i>Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Plancha -->
        <div class="modal fade" id="Plancha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Lavadora"></h1>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <img src="https://img.freepik.com/foto-gratis/mujer-primer-plano-hierro_23-2148521025.jpg?t=st=1727672139~exp=1727675739~hmac=f2b95b7f33ffa8992731cc28ffe807a6bd05ecb9fe7235da4cb91913d4eae34c&w=1380"
                                    alt="" width="100%" class="imgcarta">
                                <p><strong>Incluye:</strong> La plancha se cobra por pieza que tiene un costo de
                                    <strong>$10</strong>. <br>
                                    Al ser un total de <strong>12 piezas</strong> , el costo cambiara a un costo de
                                    <strong>$100</strong>.
                                </p>
                            </div>
                            <div class="right_modal">
                                <div id="cantidad">
                                    <div>
                                        <form id="form">
                                            <h5>PIEZAS</h5>
                                            <input type="number" id="cantidad-plancha" value="0">
                                        </form>
                                    </div>
                                </div>
                                <hr width="80%" style="margin: auto;">
                                <p class="precio"> TOTAL $<span id="total-plancha">0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar"><i class="bi bi-plus"></i>Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tintoreria -->
        <div class="modal fade" id="Tintoreria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Lavadora"></h1>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <img src="https://img.freepik.com/foto-gratis/foto-primer-plano-ropa-moda-perchas-tienda_627829-6026.jpg?t=st=1727672702~exp=1727676302~hmac=7e656b00702b37ca4fe62c619b738f9a701750c0933590f5216208b34206ff63&w=1380"
                                    alt="" width="100%" class="imgcarta">
                                <p><strong>Incluye:</strong> La tintoreria tiene un costo dependiendo del tipo de ropa
                                    que quiera lavar.
                                    La entrega de la ropa dependera del tipo de encargo y la disponibilidad de la
                                    tintoreria.
                                </p>
                            </div>
                            <div class="right_modal">
                                <div id="cantidad">
                                    <div>
                                        <form id="form">
                                            <h5>TIPO</h5>
                                            <input type="text" id="tipo-tintoreria" placeholder="Saco">
                                            <h5>COSTO</h5>
                                            <input type="number" id="costo-tintoreria" value="1">
                                            <h5>CANTIDAD</h5>
                                            <input type="number" id="cantidad-tintoreria" value="0">
                                            <h5>COLOR</h5>
                                            <input type="color" id="color-tintoreria">
                                        </form>
                                    </div>
                                </div>
                                <hr width="80%" style="margin: auto;">
                                <p class="precio"> TOTAL $<span id="total-tintoreria">0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar"><i class="bi bi-plus"></i>Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Plastico -->
        <div class="modal fade" id="Plastico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Productos</h1>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="contenido_modal">
                            <div class="left_modal">
                                <!-- Buscador de productos -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="buscar-productos"
                                        placeholder="Buscar producto...">
                                    <div id="lista-productos" class="list-group mt-2"></div>
                                </div>
                                <!-- Formulario de producto seleccionado -->
                                <div class="producto-seleccionado">
                                    <input type="hidden" id="producto-id">
                                    <div class="form-group">
                                        <label>Nombre del Producto</label>
                                        <input type="text" id="nombre-producto" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Precio Unitario</label>
                                        <input type="number" id="precio-producto" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" id="cantidad-producto" class="form-control" value="1"
                                            min="1">
                                    </div>
                                    <div class="form-group">
                                        <label>Total</label>
                                        <span id="total-producto">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-agregar" id="agregar-producto-carrito">
                            <i class="bi bi-plus"></i>Agregar al Carrito
                        </button>
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
                            <a class="nav-link active" href="Venta.php">Venta</a>
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

        <!-- linea -->
        <hr class="border border-primary border-3 opacity-75">
        <!-- contenido -->
        </div>
        <!-- Proximamente contenido publicitario debajo -->
        <div class="container">
            <div class="products">
                <h2>Productos</h2>
                <hr>
                <div id="product-buttons">
                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/polo-moda-hombre_74190-4858.jpg?t=st=1727670448~exp=1727674048~hmac=9672b83e072dfdff34a657f89e6b81c9a5ee48c82469e4cf705327a677b8275d&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Servicio Completo</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Servicio">Comprar</button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/mujer-usando-capsula-lavar-su-ropa_23-2149117042.jpg?t=st=1727671748~exp=1727675348~hmac=4db3612e611fa82cb8bb656ff40f7ac622e01533622bf284dd38b1791a5951dd&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Lavadora</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Lavadora">Comprar</button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/vista-frontal-canasta-blanca-toallas_23-2148251849.jpg?t=st=1727672109~exp=1727675709~hmac=c0dc5f46b0fa133e723983003fe56db730cf43dc8d09dae190019c3d1f8e1791&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Secadora</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Secadora">Comprar</button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/mujer-primer-plano-hierro_23-2148521025.jpg?t=st=1727672139~exp=1727675739~hmac=f2b95b7f33ffa8992731cc28ffe807a6bd05ecb9fe7235da4cb91913d4eae34c&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Plancha</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Plancha">Comprar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/foto-primer-plano-ropa-moda-perchas-tienda_627829-6026.jpg?t=st=1727672702~exp=1727676302~hmac=7e656b00702b37ca4fe62c619b738f9a701750c0933590f5216208b34206ff63&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Tintoreria</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Tintoreria">Comprar</button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="remove-when-use">
                            <label><img
                                    src="https://img.freepik.com/foto-gratis/marco-circular-vista-superior-diferentes-productos_23-2148357498.jpg?t=st=1727672765~exp=1727676365~hmac=1bd19d7dfdea3922e84df55ac93de252dc27225dcd573906467725cff1dfc257&w=1380"
                                    alt="">
                            </label>
                        </div>
                        <div class="details">
                            <label>Plasticos</label>
                            <div id="boton">
                                <button type="button" class="btn-donate btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#Plastico">Comprar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart">
                <h2>Carrito de Compras</h2>
                <hr>
                <div id="cart-items"></div>
            </div>
            <id class="checkout">
                <h2>Información del Cliente</h2>
                <hr>
                <div id="cart-items"></div>
                <div class="mb-3">
                    <input type="text" id="searchCustomer" class="form-control" placeholder="Buscar cliente...">
                    <div id="customerResults" class="list-group mt-2"></div>
                </div>
                <hr>
                <form id="customerForm" method="POST" action="agregar_cliente.php">
                    <input type="hidden" id="customerId" name="customerId">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="customerName" name="customerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerPhone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="customerPhone" name="customerPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerAddress" class="form-label">Dirección</label>
                        <textarea class="form-control" id="customerAddress" name="customerAddress" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </form>
                <hr>
                <div>
                    <h2>Total del Carrito: $<span id="total-general">0.00</span></h2>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="isPending" name="isPending">
                        <label class="form-check-label" for="isPending">Pendiente</label>
                    </div>

                    <button id="processPaymentButton" class="btn btn-success">Procesar Pago</button>
                </div>
        </div>
    </main>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/jellyTriangle.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script src="../JS/clientes.js"></script>
    <script src="../JS/productos.js"></script>
    <script src="../JS/calcular.js"></script>
    <script>
        document.getElementById('processPaymentButton').addEventListener('click', function() {
            // Obtener los productos del carrito
            const cartItems = []; // Aquí debes obtener los elementos del carrito
            const total = document.getElementById('total-general').innerText; // Total del carrito
            const customerId = document.getElementById('customerId').value; // ID del cliente

            // Recopilar los datos de los productos en el carrito
            document.querySelectorAll('#cart-items .cart-item').forEach(item => {
                const productId = item.getAttribute('data-product-id'); // Asegúrate de tener un atributo data-product-id
                const quantity = item.querySelector('.quantity').value; // Asegúrate de tener un campo para la cantidad
                cartItems.push({
                    productId,
                    quantity
                });
            });

            // Crear el objeto de datos a enviar
            const data = {
                customerId: customerId,
                total: total,
                items: cartItems
            };

            // Enviar los datos al servidor
            fetch('ruta/a/tu/endpoint/para/procesar/pago.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pago procesado con éxito');
                        // Aquí puedes redirigir o limpiar el carrito
                    } else {
                        alert('Error al procesar el pago: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar el pago');
                });
        });
    </script>
</body>


</html>