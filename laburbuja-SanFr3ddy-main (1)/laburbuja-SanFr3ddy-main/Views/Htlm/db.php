<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lavanderia";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
