<?php
session_start();
require_once "autoload.php";

use Dwes\ProyectoVideoclub\{Videoclub, Cliente, CintaVideo, Dvd, Juego};

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// usuarios válidos
$validUsers = [
    'admin' => 'admin',
    'bruce' => 'gotham',
    'clark' => 'dailyplanet',
    'diana' => 'amazon'
];

if (!isset($validUsers[$username]) || $validUsers[$username] !== $password) {
    header('Location: index.php?error=1');
    exit;
}

$vc = null;
if ($username === 'admin') {
    $vc = new Videoclub("Videoclub Premium");

    // soportes
    $vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);
    $vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");
    $vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
    $vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);
    $vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

    // clientes con usser/pass
    $vc->incluirSocio("Bruce Wayne", 3, "bruce", "gotham");
    $vc->incluirSocio("Clark Kent", 3, "clark", "dailyplanet");
    $vc->incluirSocio("Diana Prince", 3, "diana", "amazon");

    // alquileres de prueba
    $vc->alquilaSocioProducto(1, 1);
    $vc->alquilaSocioProducto(1, 2);
    $vc->alquilaSocioProducto(2, 3);
}

// guardar en sesión
$_SESSION['user'] = $username;
$_SESSION['videoclub'] = $vc;

// redereccion
if ($username === 'admin') {
    header('Location: mainAdmin.php');
} else {
    $cliente = null;
    if ($vc) {
        foreach ($vc->getSocios() as $c) {
            if ($c->getUsername() === $username && $c->getPassword() === $password) {
                $cliente = $c;
                break;
            }
        }
    }
    if ($cliente) {
        $_SESSION['cliente'] = $cliente;
        header('Location: mainCliente.php');
    } else {
        header('Location: main.php');
    }
}
exit;