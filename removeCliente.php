<?php
require_once "autoload.php";
session_start();

use Dwes\ProyectoVideoclub\Videoclub;

// Solo admin puede borrar
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getUsername() !== 'admin') {
    header('Location: index.php');
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mainAdmin.php');
    exit;
}

$numero = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;

// Validar que el número sea válido
if ($numero <= 0 || $numero === 1) { // No permitir eliminar admin (socio 1)
    $_SESSION['flash_error'] = 'No se puede eliminar este cliente.';
    header('Location: mainAdmin.php');
    exit;
}

// Obtener videoclub de la sesión
if (isset($_SESSION['videoclub']) && $_SESSION['videoclub'] instanceof Videoclub) {
    $vc = $_SESSION['videoclub'];
    $socios = $vc->getSocios();
    
    // Buscar y eliminar el socio
    $encontrado = false;
    foreach ($socios as $i => $socio) {
        if ($socio->getNumero() === $numero) {
            array_splice($socios, $i, 1);
            $encontrado = true;
            break;
        }
    }
    
    // Si existe el método setSocios, usarlo
    if ($encontrado) {
        if (method_exists($vc, 'setSocios')) {
            $vc->setSocios($socios);
        } else {
            // Alternativa: acceder directamente a la propiedad privada mediante reflection
            $reflection = new ReflectionClass($vc);
            $property = $reflection->getProperty('socios');
            $property->setAccessible(true);
            $property->setValue($vc, $socios);
        }
        $_SESSION['videoclub'] = $vc;
        $_SESSION['clientes'] = $socios;
        $_SESSION['flash_success'] = 'Cliente eliminado correctamente.';
    } else {
        $_SESSION['flash_error'] = 'Cliente no encontrado.';
    }
} else {
    $_SESSION['flash_error'] = 'Error: Videoclub no disponible.';
}

// Redirigir al listado
header('Location: mainAdmin.php');
exit;