<?php
// Inicia la sesión para mantener datos del usuario autenticado en toda la aplicación
session_start();
require_once "autoload.php";

// Importa las clases necesarias para la autenticación y gestión del videoclub
use Dwes\ProyectoVideoclub\{Videoclub};

// Recibe y almacena las credenciales enviadas desde el formulario de login
// Utiliza el operador null coalescing (??) para establecer valores por defecto (strings vacíos)
$username = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';


// INICIALIZACIÓN DEL SISTEMA DE VIDEOCLUB
// Crea una instancia del objeto Videoclub que será el contenedor principal
// para todos los socios (clientes) y productos (soportes) disponibles
$vc = new Videoclub("Videoclub");

// REGISTRO DE SOCIOS (CLIENTES) EN EL SISTEMA
// Se agregan usuarios al sistema con sus credenciales (username y password)
// El primer parámetro es el nombre, el segundo es el cupo de alquileres permitidos

// Registro del usuario administrador con cupo 0 (acceso total)
$vc->incluirSocio("Administrador", 0, "admin", "admin");

// Registro de clientes normales con cupo de 3 alquileres simultáneos
// Cada cliente tiene credenciales únicas para acceder a la aplicación
$vc->incluirSocio("Bruce Wayne", 3, "bruce", "gotham")
    ->incluirSocio("Clark Kent", 3, "clark", "dailyplanet")
    ->incluirSocio("Diana Prince", 3, "diana", "amazon")
    ->incluirSocio("Usuario de prueba", 3, "usuario", "usuario");

// CARGA DE PRODUCTOS (SOPORTES) EN EL SISTEMA

// Se agregan diferentes tipos de productos que se pueden alquilar:

// Cintas de video: parámetros (nombre, precio, duración en minutos)
$vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107);

// DVDs: parámetros (nombre, precio, idiomas disponibles, relación de aspecto)
$vc->incluirDvd("Origen", 15, "es,en,fr", "16:9");

// Videojuegos: parámetros (nombre, precio, plataforma, cupo mínimo, cupo máximo)
$vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
$vc->incluirJuego("FIFA 23", 59.99, "PS5", 1, 4);

// Más DVDs con información de idiomas y formato
$vc->incluirDvd("El Imperio Contraataca", 12, "es,en", "4:3");

// GESTIÓN DE ALQUILERES DE PRUEBA

// Se generan algunos alquileres iniciales para demostración:
// Parámetros: (idSocio, idProducto)

$vc->alquilaSocioProducto(5, 3);
$vc->alquilaSocioProducto(2, 2);
//print_r($vc->getSocios()[1]);
//alert();

// VALIDACIÓN DE CREDENCIALES DE ACCESO

// Obtiene la lista completa de socios registrados en el sistema
$socios = $vc->getSocios();

// Búsqueda optimizada del usuario: recorre la lista de socios comparando
// el username y password recibidos con los almacenados en la base de datos en memoria
// Si encuentra una coincidencia exacta, almacena el objeto socio y detiene la búsqueda
$usuarioEncontrado = null;
foreach ($socios as $socio) {
    if ($socio->getUsername() === $username && $socio->getPassword() === $password) {
        $usuarioEncontrado = $socio;
        break;
    }
}



// PROCESAMIENTO DE AUTENTICACIÓN Y REDIRECCIÓN

// Si se encuentra un usuario válido con credenciales coincidentes
if ($usuarioEncontrado) {
    // Almacena el objeto del usuario autenticado en la sesión para usarlo
    // en las páginas internas de la aplicación
    $_SESSION['user'] = $usuarioEncontrado;

    // Almacena la instancia del videoclub en la sesión para acceder a
    // todos los socios, productos y alquileres desde cualquier página
    $_SESSION['videoclub'] = $vc;

    // Verifica el tipo de usuario y redirige a la página correspondiente
    if ($username === 'admin') {
        // Para administradores: carga la lista de soportes (productos) y
        // clientes (socios) en la sesión para mostrar en el panel de control
        $_SESSION['soportes'] = $vc->getProductos();
        $_SESSION['clientes'] = $vc->getSocios();
        // Redirige al panel de administración
        header('Location: mainAdmin.php');
    } else {
        // Para clientes normales: redirige a su área personal
        header('Location: mainCliente.php');
    }
    exit;
} else {
    // Si las credenciales no coinciden con ningún usuario registrado,
    // redirige al login con parámetro de error
    header('Location: index.php?error=1');
    exit;
}
