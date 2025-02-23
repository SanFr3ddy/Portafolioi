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
        <link rel="stylesheet" href="../css/estilos.css">
    </head>

    <body>
        <div class="container">
            <header>
                <!-- place navbar here -->
            </header>
            <main class="content text-center">
                <img src="../img/Titulo.png" alt="" width="200px">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        
                        <canvas id="canvas"></canvas>
                    </div>
                </div>
                <div id="game-controls" class="mt-3" style="display: none;">
                    <button class="btn btn-primary mt-3" onclick="reset()">Reiniciar</button>
                    <div id="score" class="mt-3"><h1>Puntos: 0</h1></div>
                    <div id="lives" class="mt-3"><h1>Vidas: 3</h1></div>
                    <div id="timer" class="mt-3"><h1>Tiempo: 60 seconds</h1></div>
                    <div id="game-over" class="mt-3"><h1>Game Over</h1></div>
                    <div id="final-time" class="mt-3"></div>
                    <div id="final-score" class="mt-3"></div>
                    <div id="controls" class="mt-3">
                        <button class="btn btn-secondary" onclick="togglePause()">Pausar/Reanudar</button>
                        <button class="btn btn-secondary" onclick="toggleMusic()">Music On/Off</button>
                    </div>
                </div>
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
        <script src="../Js/canva.js"></script>
        <script src="../Js/fondo.js"></script> 
    </body>
</html>