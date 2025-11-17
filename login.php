<?php
session_start();
require_once "autoload.php";

use Dwes\ProyectoVideoclub\{Cliente, Videoclub};

$username = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';


// Creamos el objeto Videoclub y añadimos los socios y soportes
$vc = new Videoclub("Videoclub");

// Añadimos los socios
// Añadimos usuario admin
$vc->incluirSocio("Administrador", 0, "admin", "admin");
// Añadimos usuarios clientes
$vc->incluirSocio("Bruce Wayne", 3, "bruce", "gotham");
$vc->incluirSocio("Clark Kent", 3, "clark", "dailyplanet");
$vc->incluirSocio("Diana Prince", 3, "diana", "amazon");

$vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);
$vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");
$vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
$vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);
$vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

$vc->alquilaSocioProducto(1, 1);
$vc->alquilaSocioProducto(1, 2);
$vc->alquilaSocioProducto(2, 3);

//print_r($vc->getSocios()[1]);
//alert();
$socios = $vc->getSocios();
// Comprobación óptima: busca usuario con username y contraseña coincidentes
$usuarioEncontrado = null;
foreach ($socios as $socio) {
    if ($socio->getUsername() === $username && $socio->getPassword() === $password) {
        $usuarioEncontrado = $socio;
        break;
    }
}

// Si se encuentra un usuario válido
if ($usuarioEncontrado) {
    $_SESSION['user'] = $usuarioEncontrado;
    $_SESSION['videoclub'] = $vc;

    if ($username === 'admin') {
        // Obtener soportes y socios directamente del videoclub
        $_SESSION['soportes'] = $vc->getProductos();
        $_SESSION['clientes'] = $vc->getSocios();
        header('Location: mainAdmin.php');
    } else {
        header('Location: mainCliente.php');
    }
    exit;
}

// Si no se encuentra, error
header('Location: index.php?error=1');
exit;
