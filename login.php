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

// Creamos el objeto Videoclub y añadimos los socios y soportes
$vc = new Videoclub("Videoclub");
$vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);
$vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");
$vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
$vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);
$vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

// Añadimos los socios
$vc->incluirSocio("Bruce Wayne", 3, "bruce", "gotham");
$vc->incluirSocio("Clark Kent", 3, "clark", "dailyplanet");
$vc->incluirSocio("Diana Prince", 3, "diana", "amazon");

// Comprobamos si el usuario es admin o usuario genérico
if (isset($validUsers[$username]) && $validUsers[$username] === $password) {
    // Creamos el objeto Cliente con los datos de login y lo guardamos en la sesión.
    $cliente = new Cliente($username, 1, 3, $username, $password);
    $_SESSION['user'] = $cliente;

    if ($username === 'admin') {
        // Datos de prueba para el administrador
        $soportes = [
            ['numero' => 1, 'titulo' => 'Los cazafantasmas', 'precio' => 3.5, 'tipo' => 'CintaVideo', 'alquilado' => true],
            ['numero' => 2, 'titulo' => 'Origen', 'precio' => 15, 'tipo' => 'Dvd', 'alquilado' => true],
            ['numero' => 3, 'titulo' => 'The Last of Us Part II', 'precio' => 49.99, 'tipo' => 'Juego', 'alquilado' => true],
            ['numero' => 4, 'titulo' => 'FIFA 23', 'precio' => 59.99, 'tipo' => 'Juego', 'alquilado' => false],
            ['numero' => 5, 'titulo' => 'El Imperio Contraataca', 'precio' => 12, 'tipo' => 'Dvd', 'alquilado' => false],
        ];

        $clientes = [
            ['numero' => 1, 'nombre' => 'Bruce Wayne', 'username' => 'bruce', 'password' => 'gotham', 'alquileres' => [1, 2]],
            ['numero' => 2, 'nombre' => 'Clark Kent', 'username' => 'clark', 'password' => 'dailyplanet', 'alquileres' => [3]],
            ['numero' => 3, 'nombre' => 'Diana Prince', 'username' => 'diana', 'password' => 'amazon', 'alquileres' => []],
        ];

        $_SESSION['soportes'] = $soportes;
        $_SESSION['clientes'] = $clientes;
        $_SESSION['videoclub'] = $vc;
        header('Location: mainAdmin.php');
    } else {
        $_SESSION['videoclub'] = $vc;
        header('Location: mainCliente.php');
    }
    exit;
}

// Si no es admin/usuario, comprobamos si es un socio registrado
$socioEncontrado = null;
foreach ($vc->getSocios() as $socio) {
    if ($socio->getUsername() === $username && $socio->getPassword() === $password) {
        $socioEncontrado = $socio;
        break;
    }
}

if ($socioEncontrado) {
    $_SESSION['user'] = $socioEncontrado;
    $_SESSION['videoclub'] = $vc;
    header('Location: mainCliente.php');
    exit;
}

// Si no se encuentra, error
header('Location: index.php?error=1');
exit;
