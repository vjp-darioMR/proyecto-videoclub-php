<?php
session_start();

// Solo admin puede borrar
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getNombre() !== 'admin') {
    header('Location: index.php');
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mainAdmin.php');
    exit;
}

$numero = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;

if (isset($_SESSION['videoclub']) && is_object($_SESSION['videoclub'])) {
    $vc = $_SESSION['videoclub'];
    $socios = $vc->getSocios();
    foreach ($socios as $i => $socio) {
        if ($socio->getNumero() === $numero) {
            array_splice($socios, $i, 1);
            break;
        }
    }
    
    if (method_exists($vc, 'setSocios')) {
        $vc->setSocios($socios);
    } else {
        $vc->socios = $socios;
    }
    $_SESSION['videoclub'] = $vc;
}


if (isset($_SESSION['clientes']) && is_array($_SESSION['clientes'])) {
    foreach ($_SESSION['clientes'] as $i => $cliente) {
        $num = is_array($cliente) ? ($cliente['numero'] ?? null) : ($cliente->getNumero() ?? null);
        if ($num == $numero) {
            array_splice($_SESSION['clientes'], $i, 1);
            break;
        }
    }
}

// Mensaje flash
$_SESSION['flash_success'] = 'Cliente eliminado correctamente.';

// Redirigir al listado
header('Location: mainAdmin.php');
exit;