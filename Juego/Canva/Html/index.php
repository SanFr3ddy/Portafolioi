<!doctype html>
<html lang="en">
    <head>
        <title>Juego</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="../css/login.css">
    </head>

    <body>
        <div class="container">
            <header>
                <!-- place navbar here -->
            </header>
            <main class="content text-center">
                <div class="form_area">
                    <img src="../img/Titulo.png" alt="" width="200px">
                    <p class="title"></p>
                    <form action="">
                        <div class="form_group">
                            <label class="sub_title" for="name">Name</label>
                            <input placeholder="Enter your full name" class="form_style" type="text">
                        </div>
                        <div class="form_group">
                            <label class="sub_title" for="email">Email</label>
                            <input placeholder="Enter your email" id="email" class="form_style" type="email">
                        </div>
                        <div class="form_group">
                            <label class="sub_title" for="password">Password</label>
                            <input placeholder="Enter your password" id="password" class="form_style" type="password">
                        </div>
                        <div>
                            <button class="btn">SIGN UP</button>
                            <p>Have an Account? <a class="link" href="">Login Here!</a></p>
                        </div>
                    </form>
                </div>
                <canvas id="canvas1"></canvas>
            </main>
        </div>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
        <script src="../js/fondo.js"></script>
    </body>
</html>