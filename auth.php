<?php
session_start();

// Depuración
echo "Rol de usuario en sesión: " . $_SESSION['user_role'] . "<br>";

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_role'] == 'admin') {
    include("menu.php");
} else if ($_SESSION['user_role'] == 'client') {
    include("meno_cliente.php");
} else {
    echo "Rol de usuario no válido.";
    exit();
}
?>
