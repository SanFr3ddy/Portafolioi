
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/style.css" />

  <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
  <link rel="shortcut icon" href="../Img/logo.png" type="png">
  <link rel="stylesheet" href="../css/fondo.css">
  <link rel="stylesheet" href="../css/Inicio.css">

</head>

<body>
  <nav class="sidebar close">
    <header class="logo">
      <div class="image-text">
        <span class="image">
          <a href="../Html/Inicio.html">
            <img src="../Img/logo.png" alt="" />
          </a>
        </span>

        <div class="text logo-text">
          <span class="name">Alb</span>
          <span class="profession">Alberca tu vida</span>
        </div>
      </div>

      <i class="bx bx-chevron-right toggle"></i>
    </header>

    <div class="menu-bar">
      <div class="menu">
        <li class="search-box">
          <i class="bx bx-search icon"></i>
          <input type="text" placeholder="Buscar Producto..." />
        </li>

        <ul class="menu-links">
          <li class="nav-link active">
            <a href="../Html/Inicio.php">
              <i class="bx bx-home-alt icon"></i>
              <span class="text nav-text">Inicio</span>
            </a>
          </li>

          <li class="nav-link a">
            <a href="../Html/Catalogo.html">
              <i class="bx bx-collection icon"></i>
              <span class="text nav-text">Catalogo</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="../Html/Recomendaciones.html">
              <i class="bx bx-pie-chart-alt icon"></i>
              <span class="text nav-text">Recomendaciones</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <i class="bx bx-bell icon"></i>
              <span class="text nav-text">Notificaciones</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="#">
              <i class="bx bx-heart icon"></i>
              <span class="text nav-text">Mis gusta</span>
            </a>
          </li>

          <li class="nav-link">
            <a href="../Html/Carrito.html">
              <i class="bx bx-cart icon"></i>
              <span class="text nav-text">Carrito</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="bottom-content">
        <li class="">
          <a href="logout.php">
            <i class="bx bx-log-out icon"></i>
            <span class="text nav-text">Cerrar sesión</span>
          </a>
        </li>
      </div>
    </div>
  </nav>
  <main class="main">
    <h1>Alberca tu vida</h1>
    <div class="gallery">
    <h1>Bienvenido, <?php echo $_SESSION['usuarios']; ?>!</h1>
    <p>Esta es la página de inicio.</p>
    <a href="logout.php">Cerrar sesión</a>
    </div>
  </main>
  <script src="../Js/script.js"></script>
  <script src='https://codepen.io/wodniack/pen/YzmgGzP/b66656b6c21a73b4ca05fda86662cc3a.js'></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>