<?php
session_start();

// Usuarios válidos
$usuarios = [
    'admin'   => 'admin',
    'usuario' => 'usuario'
];

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// Validar
if (isset($usuarios[$usuario]) && $usuarios[$usuario] === $password) {
    $_SESSION['usuario'] = $usuario;
    header("Location: main.php");
    exit;
} else {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: index2.php");
    exit;
}
?>