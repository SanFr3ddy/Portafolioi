<?php
session_start();
require_once 'check_admin.php'; // Asegúrate de que la ruta sea correcta
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
  <!-- Emoticonos -->
  <link href="/your-path-to-uicons/css/uicons-rounded-regular.css" rel="stylesheet">
  <link href="/your-path-to-uicons/css/uicons-rounded-bold.css" rel="stylesheet">
  <link href="/your-path-to-uicons/css/uicons-rounded-solid.css" rel="stylesheet">
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="Inicio.php">Inicio</a>
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
    <!-- linea -->
    <hr class="border border-primary border-3 opacity-75">
    <!-- contenido izquierdo -->
    <div class="contenido">
      <div class="left">
        <h1>LAVANDERIA "LA BURBUJA"</h1>
        <br>
        <p>
          Su solución integral para el cuidado de su ropa.
          En Lavanderia La Burbuja, nos dedicamos a ofrecer una amplia gama de servicios que le permitirán disfrutar de
          prendas impecables.
          <br><br><strong>Ofrecemos:</strong>

          <br><strong>Lavado:</strong> Con nuestros equipos modernos y detergentes de alta calidad, su ropa quedará
          limpia y
          fresca, cuidando de cada fibra. <br>

          <strong>Secado:</strong> Le brindamos un servicio de secado rápido y eficiente, evitando las molestas manchas
          de humedad.
          <br><strong>Planchado:</strong> Le devolvemos su ropa perfectamente planchada, sin arrugas ni pliegues.
          <br><strong>Tintorería:</strong> Para prendas delicadas que requieren un cuidado especial, contamos con un
          servicio profesional de tintorería.
          <br><strong>Serivio por encargo:</strong>Al pesar la cantidad de ropa que usted necesita le daremos un
          estimado del costo (esto varia dependiendo el peso) y se lo entregaremos totalmente listo para solo pasar a
          recogerlo. Incluye lavado y secado.

          <br><br>En La Burbuja, nos esforzamos por brindar un servicio personalizado, rápido y eficiente, a precios
          competitivos. Nuestro equipo estará encantado de asesorarle para que su ropa reciba el mejor cuidado.

          Le invitamos a que nos visite o se ponga en contacto con nosotros para obtener más información sobre nuestros
          servicios.

          <br>Atentamente, El equipo de Lavandería La Burbuja.
        </p>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d233.45566464937428!2d-103.38832460210429!3d20.57618543945249!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8428ad43c2bb5731%3A0xaeed45dcc80872a8!2sLavander%C3%ADa%20La%20Burbuja!5e0!3m2!1ses!2smx!4v1727667214774!5m2!1ses!2smx"
          width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <!-- contenido lado derecho -->
      <div class="right">
        <img src="../Img/WhatsApp Image 2024-08-10 at 18.47.34.jpeg" alt="" class="imagenvolante">
      </div>
    </div>

    <!-- Proximamente contenido publicitario debajo -->
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/jellyTriangle.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</body>

</html>