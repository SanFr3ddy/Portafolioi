<?php
session_start();
include 'db.php';

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    

    $sql = "SELECT * FROM usuarios_listado WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($contraseña, $row['contraseña'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];
            header("Location: inicio.php");
            exit();
        } else {
            $loginError = "Contraseña incorrecta.";
        }
    } else {
        $loginError = "Usuario no encontrado.";
    }
    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <title>Burbuja</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="../Css/Login.css">
    <link rel="shortcut icon" href="../Img/imagen_2024-08-19_080627485-removebg-preview (1).png" type="png">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <!-- Inicio -->
        <form class="form-control" method="POST">
            <p class="title">BIENVENIDO</p>
            <?php if ($loginError): ?>
                <div class="alert alert-danger"><?php echo $loginError; ?></div>
            <?php endif; ?>
            <div class="input-field">
                <input required="" class="input" type="text" name="usuario" />
                <label class="label" for="input">Usuario</label>
            </div>
            <div class="input-field">
                <input required="" class="input" type="password" name="contraseña" />
                <label class="label" for="input">Contraseña</label>
            </div>
            <button class="submit-btn" type="submit">Ingresar</button>
        </form>


        <!-- Circulos izquierda -->
        <div>
            <div class='box'>
                <div class='wave -one'></div>
                <div class='wave -two'></div>
                <div class='wave -three'></div>
            </div>
        </div>
        <div>
            <img src="../Views/imagen_2024-08-19_080627485-removebg-preview.png" alt="" class="Img_lavanderia">
        </div>

    </main>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>