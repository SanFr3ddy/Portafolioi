<?php
function is_admin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 'Admin';
}

function require_admin() {
    if (!is_admin()) {
        header("Location: inicio.php");
        exit();
    }
}
?>