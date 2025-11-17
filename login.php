<?php
session_start();
require_once "autoload.php";

use Dwes\ProyectoVideoclub\{Cliente, Videoclub};

$username = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// usuarios válidos
$validUsers = [
    'admin' => 'admin',
    'usuario' => 'usuario'
];

if (!isset($validUsers[$username]) || $validUsers[$username] !== $password) {
    header('Location: index.php?error=1');
    exit;
}

//La variable de videoclub empieza nula
$vc = new Videoclub("Videoclub");
// crear cliente
$cliente = new Cliente($username, 1, 3, $username, $password);
// guardar en sesión
$_SESSION['user'] = $cliente;

if ($username === 'admin') {
    $vc = new Videoclub("Videoclub");

    // soportes
    $vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);
    $vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");
    $vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
    $vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);
    $vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

    // clientes con user/pass
    $vc->incluirSocio("Bruce Wayne", 3, "bruce", "gotham");
    $vc->incluirSocio("Clark Kent", 3, "clark", "dailyplanet");
    $vc->incluirSocio("Diana Prince", 3, "diana", "amazon");

    // alquileres de prueba
    $vc->alquilaSocioProducto(1, 1);
    $vc->alquilaSocioProducto(1, 2);
    $vc->alquilaSocioProducto(2, 3);

    $_SESSION['videoclub'] = $vc;
    header('Location: mainAdmin.php');
} else if ($username === 'usuario') {
    $vc = new Videoclub("Videoclub");

    // soportes
    $vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);
    $vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");
    $vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
    $vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);
    $vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

    $_SESSION['videoclub'] = $vc;
    header('Location: mainCliente.php');
}

exit;
