<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://public.codepenassets.com/css/normalize-5.0.0.min.css">
    <link rel="stylesheet" href="../css/fondo.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="shortcut icon" href="../Img/logo.png" type="png">
</head>

<body>
    <a-waves>
        <svg class="js-svg"></svg>
    </a-waves>
    <section class="home">
        <div id="form-ui">
            <form action="./inicio.php" method="post" id="form">
                <div id="form-body">
                    <div id="welcome-lines">
                        <div id="welcome-line-1">Albercas</div>
                        <div id="welcome-line-2">Es hora de cuidar tu piscina</div>
                    </div>
                    <div id="input-area">
                        <div class="form-inp">
                            <input name="email" placeholder="Correo electrónico" type="text" required>
                        </div>
                        <div class="form-inp">
                            <input name="password" placeholder="Contraseña" type="password" required>
                        </div>
                    </div>
                    <div id="submit-button-cvr">
                        <button id="submit-button" type="submit">Ingresar</button>
                    </div>
                    <div id="forgot-pass">
                        <a href="registro.html">¿No tienes cuenta?<strong> Regístrate aquí</strong></a>
                    </div>
                    <?php if (isset($error)): ?>
                        <p style="color: red; text-align: center;"><?php echo $error; ?></p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <script src='https://codepen.io/wodniack/pen/YzmgGzP/b66656b6c21a73b4ca05fda86662cc3a.js'></script>
    <script src="../Js/fondo.js"></script>
</body>

</html>