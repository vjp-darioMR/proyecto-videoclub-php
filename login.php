<?php
// Iniciamos la sesión para almacenar información del usuario y del videoclub
session_start();
require_once "autoload.php";

// Importamos las clases necesarias
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

// Creamos el objeto Cliente con los datos de login y lo guardamos en la sesión.
// El objeto Cliente se usa por la app para identificar al usuario actual.
$vc = new Videoclub("Videoclub");
$cliente = new Cliente($username, 1, 3, $username, $password);
// Guardamos la instancia del usuario en la sesión para consultas posteriores
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

    /* --- Datos de prueba para administrador ---
     Además de guardar el objeto Videoclub en la sesión, aquí creamos
     arrays asociativos simples ($_SESSION['soportes'] y
     $_SESSION['clientes']) que contienen los datos que usaremos en
     mainAdmin.php para mostrar listados. La intención es simular
     datos */
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

    /* 
    Guardamos los arrays en la sesión. Estos arrays son estructuras
        sencillas y facilitan renderizar listados
        sin depender únicamente de los métodos del objeto Videoclub.*/
    $_SESSION['soportes'] = $soportes;
    $_SESSION['clientes'] = $clientes;

    /* También mantenemos el objeto videoclub por compatibilidad con
     el código existente que lo utiliza (fallback en mainAdmin.php).*/
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
