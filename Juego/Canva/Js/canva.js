const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
canvas.width = 640;
canvas.height = 480;

let score = 0;
let lives = 3;
let gameOver = false;
let isPaused = false;
let musicOn = true;
let shieldActive = false;
let shieldDuration = 5000; // 5 segundos
let level = 1;
let timer = 60; // 1 minuto
let totalTime = 0; // Tiempo total transcurrido
let gameStarted = false;

const player = {
    x: canvas.width / 2,
    y: canvas.height - 30,
    width: 50,
    height: 50,
    speed: 5,
    dx: 0,
    dy: 0,
    normalImage: new Image(),
    nearImage: new Image(),
    currentImage: null,
    nearObstacle: false
};

player.normalImage.src = '../img/manos.png'; // Cambia esto a la ruta de tu imagen normal
player.nearImage.src = '../img/manocerrada.png'; // Cambia esto a la ruta de tu imagen cerca
player.currentImage = player.normalImage;

const obstacleImage = new Image();
obstacleImage.src = '../img/2604380.png'; // Cambia esto a la ruta de tu imagen

const obstacle = {
    x: Math.random() * (canvas.width - 50),
    y: 0,
    width: 50,
    height: 80,
    speed: 3
};

const rectangles = [];
const specialObjects = [];

function drawPlayer() {
    if (shieldActive) {
        ctx.strokeStyle = 'cyan';
        ctx.lineWidth = 5;
        ctx.strokeRect(player.x, player.y, player.width, player.height);
    }
    ctx.drawImage(player.currentImage, player.x, player.y, player.width, player.height);
}

function drawObstacle() {
    ctx.drawImage(obstacleImage, obstacle.x, obstacle.y, obstacle.width, obstacle.height);
}

function drawRectangles() {
    ctx.fillStyle = 'red';
    rectangles.forEach(rect => {
        ctx.fillRect(rect.x, rect.y, rect.width, rect.height);
    });
}

function drawSpecialObjects() {
    ctx.fillStyle = 'blue';
    specialObjects.forEach(obj => {
        ctx.beginPath();
        ctx.arc(obj.x, obj.y, obj.radius, 0, Math.PI * 2);
        ctx.fill();
    });
}

function drawBackground() {
    if (gameOver) {
        ctx.fillStyle = 'black';
    } else if (lives === 3) {
        ctx.fillStyle = 'black';
    } else if (lives === 2) {
        ctx.fillStyle = 'black';
    } else if (lives === 1) {
        ctx.fillStyle = 'black';
    }
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}

function clear() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function newPos() {
    player.x += player.dx;
    player.y += player.dy;

    // Detectar paredes
    if (player.x < 0) {
        player.x = 0;
    }
    if (player.x + player.width > canvas.width) {
        player.x = canvas.width - player.width;
    }
    if (player.y < 0) {
        player.y = 0;
    }
    if (player.y + player.height > canvas.height) {
        player.y = canvas.height - player.height;
    }
}

function moveObstacle() {
    obstacle.y += obstacle.speed;

    if (obstacle.y > canvas.height) {
        obstacle.y = 0;
        obstacle.x = Math.random() * (canvas.width - obstacle.width);
        if (!shieldActive) {
            lives--;
            document.getElementById('lives').innerText = 'Vidas: ' + lives;
            if (lives === 0) {
                gameOver = true;
                document.getElementById('game-over').style.display = 'block';
                document.getElementById('final-time').innerText = 'Tiempo: ' + totalTime + ' segundos';
                document.getElementById('final-score').innerText = 'Puntuación Final: ' + score;
            }
        }
    }

    if (detectCollision(player, obstacle)) {
        score += 100;
        document.getElementById('score').innerText = 'Puntuación: ' + score;
        resetObstacle();
        // Cambiar la imagen del jugador durante 1 segundo
        player.currentImage = player.nearImage;
        setTimeout(() => {
            player.currentImage = player.normalImage;
        }, 1000); // Cambia la imagen de nuevo después de 1 segundo
    }
}

function moveRectangles() {
    rectangles.forEach((rect, index) => {
        rect.y += rect.speed;
        if (rect.y > canvas.height) {
            rectangles.splice(index, 1);
        }
        if (detectCollision(player, rect)) {
            if (!shieldActive) {
                lives--;
                document.getElementById('lives').innerText = 'Vidas: ' + lives;
                if (lives === 0) {
                    gameOver = true;
                    document.getElementById('game-over').style.display = 'block';
                    document.getElementById('final-time').innerText = 'Tiempo: ' + totalTime + ' segundos';
                    document.getElementById('final-score').innerText = 'Puntuación Final: ' + score;
                }
            }
        }
    });
}

function moveSpecialObjects() {
    specialObjects.forEach((obj, index) => {
        obj.y += obj.speed;
        if (obj.y > canvas.height) {
            specialObjects.splice(index, 1);
        }
        if (detectCircleCollision(player, obj)) {
            applySpecialEffect(obj.type);
            specialObjects.splice(index, 1);
        }
    });
}

function detectCollision(rect1, rect2) {
    return rect1.x < rect2.x + rect2.width &&
           rect1.x + rect1.width > rect2.x &&
           rect1.y < rect2.y + rect2.height &&
           rect1.y + rect1.height > rect2.y;
}

function detectCircleCollision(rect, circle) {
    const distX = Math.abs(circle.x - rect.x - rect.width / 2);
    const distY = Math.abs(circle.y - rect.y - rect.height / 2);

    if (distX > (rect.width / 2 + circle.radius)) { return false; }
    if (distY > (rect.height / 2 + circle.radius)) { return false; }

    if (distX <= (rect.width / 2)) { return true; }
    if (distY <= (rect.height / 2)) { return true; }

    const dx = distX - rect.width / 2;
    const dy = distY - rect.height / 2;
    return (dx * dx + dy * dy <= (circle.radius * circle.radius));
}

function resetObstacle() {
    obstacle.y = 0;
    obstacle.x = Math.random() * (canvas.width - obstacle.width);
}

function update() {
    if (!gameStarted) {
        drawMenu();
    } else if (!gameOver && !isPaused) {
        clear();
        drawBackground();
        drawPlayer();
        drawObstacle();
        drawRectangles();
        drawSpecialObjects();
        newPos();
        moveObstacle();
        moveRectangles();
        moveSpecialObjects();
        checkNearObstacle();
    }
    requestAnimationFrame(update);
}

function reset() {
    score = 0;
    lives = 3;
    gameOver = false;
    shieldActive = false;
    specialObjects.length = 0;
    rectangles.length = 0;
    level = 1;
    timer = 60;
    totalTime = 0;
    document.getElementById('score').innerText = 'Puntuación: ' + score;
    document.getElementById('lives').innerText = 'Vidas: ' + lives;
    document.getElementById('game-over').style.display = 'none';
    document.getElementById('final-time').innerText = '';
    document.getElementById('final-score').innerText = '';
    player.x = canvas.width / 2;
    player.y = canvas.height - 30;
    resetObstacle();
    startTimer();
}

function togglePause() {
    isPaused = !isPaused;
}

function toggleMusic() {
    musicOn = !musicOn;
    // Agregar lógica para reproducir/pausar música
}

function moveRight() {
    player.dx = player.speed;
}

function moveLeft() {
    player.dx = -player.speed;
}

function moveUp() {
    player.dy = -player.speed;
}

function moveDown() {
    player.dy = player.speed;
}

function keyDown(e) {
    if (e.key === 'ArrowRight' || e.key === 'Right') {
        moveRight();
    } else if (e.key === 'ArrowLeft' || e.key === 'Left') {
        moveLeft();
    } else if (e.key === 'ArrowUp' || e.key === 'Up') {
        moveUp();
    } else if (e.key === 'ArrowDown' || e.key === 'Down') {
        moveDown();
    }
}

function keyUp(e) {
    if (
        e.key === 'ArrowRight' ||
        e.key === 'Right' ||
        e.key === 'ArrowLeft' ||
        e.key === 'Left' ||
        e.key === 'ArrowUp' ||
        e.key === 'Up' ||
        e.key === 'ArrowDown' ||
        e.key === 'Down'
    ) {
        player.dx = 0;
        player.dy = 0;
    }
}

function applySpecialEffect(type) {
    if (type === 'extraLife') {
        lives++;
        document.getElementById('lives').innerText = 'Vidas: ' + lives;
    } else if (type === 'extraSquare') {
        // Agregar lógica para agregar un cuadrado extra
    } else if (type === 'shield') {
        shieldActive = true;
        setTimeout(() => {
            shieldActive = false;
        }, shieldDuration);
    }
}

function spawnSpecialObject() {
    const types = ['extraLife', 'extraSquare', 'shield'];
    const type = types[Math.floor(Math.random() * types.length)];
    const specialObject = {
        x: Math.random() * (canvas.width - 20) + 10,
        y: 0,
        radius: 10,
        speed: 2,
        type: type
    };
    specialObjects.push(specialObject);
}

function spawnRectangle() {
    const rectangle = {
        x: Math.random() * (canvas.width - 50),
        y: 0,
        width: 50,
        height: 20,
        speed: 3 + level // Aumentar velocidad con el nivel
    };
    rectangles.push(rectangle);
}

function startTimer() {
    const timerInterval = setInterval(() => {
        if (!isPaused && !gameOver) {
            timer--;
            totalTime++;
            document.getElementById('timer').innerText = 'Tiempo: ' + timer + ' segundos';
            if (timer === 0) {
                level++;
                timer = 60;
                obstacle.speed += 1; // Aumentar velocidad del obstáculo
                spawnRectangle(); // Generar un nuevo rectángulo
            }
        }
        if (gameOver) {
            clearInterval(timerInterval);
        }
    }, 1000);
}

function startGame() {
    gameStarted = true;
    document.getElementById('game-controls').style.display = 'block';
    reset();
}

function drawMenu() {
    clear();
    ctx.fillStyle = 'black';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'white';
    ctx.font = '30px Arial';
    ctx.fillText('Menú', canvas.width / 2 - 50, canvas.height / 2 - 100);
    ctx.fillText('Jugar', canvas.width / 2 - 30, canvas.height / 2);
    ctx.fillText('Opciones', canvas.width / 2 - 50, canvas.height / 2 + 50);

    canvas.addEventListener('click', handleMenuClick);
}

function handleMenuClick(event) {
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    if (x >= canvas.width / 2 - 30 && x <= canvas.width / 2 + 30 && y >= canvas.height / 2 - 20 && y <= canvas.height / 2 + 20) {
        canvas.removeEventListener('click', handleMenuClick);
        startGame();
    } else if (x >= canvas.width / 2 - 50 && x <= canvas.width / 2 + 50 && y >= canvas.height / 2 + 30 && y <= canvas.height / 2 + 70) {
        alert('Opciones seleccionadas');
    }
}

function checkNearObstacle() {
    if (detectCollision(player, obstacle)) {
        player.currentImage = player.nearImage;
        player.nearObstacle = true;
        setTimeout(() => {
            player.currentImage = player.normalImage;
            player.nearObstacle = false;
        }, 200); // Cambia la imagen de nuevo después de 200ms
    }
}

document.addEventListener('keydown', keyDown);
document.addEventListener('keyup', keyUp);

setInterval(spawnSpecialObject, 10000); // Generar un objeto especial cada 10 segundos
setInterval(spawnRectangle, 2000); // Generar un rectángulo cada 2 segundos

update();