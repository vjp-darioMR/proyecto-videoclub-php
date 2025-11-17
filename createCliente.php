<?php
require_once __DIR__ . '/autoload.php';
session_start();

// Control de acceso básico
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getNombre() !== 'admin') {
    header('Location: index.php');
    exit;
}

// Sólo aceptamos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: formCreateCliente.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$maxAlq = isset($_POST['maxAlquiler']) ? (int)$_POST['maxAlquiler'] : 3;

$errors = [];
if ($nombre === '') {
    $errors[] = 'El nombre es obligatorio.';
}
if ($username === '') {
    $errors[] = 'El usuario (username) es obligatorio.';
}
if ($maxAlq < 1) {
    $errors[] = 'El máximo de alquileres debe ser al menos 1.';
}

// Guardar datos temporales para rellenar el formulario en caso de error
$_SESSION['form_data'] = ['nombre' => $nombre, 'username' => $username, 'password' => $password, 'maxAlquiler' => $maxAlq];

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: formCreateCliente.php');
    exit;
}

// Si existe el objeto Videoclub en sesión, usar su API. Si no, usar el array de prueba.
if (isset($_SESSION['videoclub']) && is_object($_SESSION['videoclub'])) {
    $vc = $_SESSION['videoclub'];
    // usar incluirSocio para que mantenga contadores internos
    $vc->incluirSocio($nombre, $maxAlq, $username, $password);
    // guardar objeto actualizado en sesión
    $_SESSION['videoclub'] = $vc;
    // Si también existe el array de clientes en sesión (mainAdmin lo usa), mantenerlo sincronizado
    if (isset($_SESSION['clientes']) && is_array($_SESSION['clientes'])) {
        $socios = $vc->getSocios();
        $ultimo = end($socios);
        if ($ultimo) {
            $_SESSION['clientes'][] = [
                'numero' => $ultimo->getNumero(),
                'nombre' => $ultimo->getNombre(),
                'username' => $ultimo->getUsername(),
                'password' => $ultimo->getPassword(),
                'alquileres' => []
            ];
        }
    }
} else {
    // estructura de array simple usada en `mainAdmin.php`
    $nuevo = [
        'nombre' => $nombre,
        'username' => $username,
        'password' => $password,
        'alquileres' => []
    ];
    if (!isset($_SESSION['clientes']) || !is_array($_SESSION['clientes'])) {
        $_SESSION['clientes'] = [];
    }
    $_SESSION['clientes'][] = $nuevo;
}

// Limpieza de datos temporales
unset($_SESSION['form_data'], $_SESSION['form_errors']);

// Asegurar que también existen los arrays de soportes en sesión
if (!isset($_SESSION['soportes']) || !is_array($_SESSION['soportes'])) {
    $_SESSION['soportes'] = [];
}

// Mensaje flash de éxito
$_SESSION['flash_success'] = 'Cliente creado correctamente.';

// Redirigir de nuevo al panel admin para ver el cliente insertado
header('Location: mainAdmin.php');
exit;
