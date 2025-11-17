<?php
require_once __DIR__ . '/autoload.php';
session_start();

// Control de acceso básico
$usuarioActual = $_SESSION['user'] ?? null;
if (!isset($usuarioActual)) {
    header('Location: index.php');
    exit;
}

// Sólo aceptamos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mainAdmin.php');
    exit;
}

// Obtener datos del formulario
$numero = (int)($_POST['numero'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$maxAlq = isset($_POST['maxAlquiler']) ? (int)$_POST['maxAlquiler'] : 3;
$origen = $_POST['origen'] ?? 'mainAdmin';

// Validaciones
$errors = [];

if ($numero <= 0) {
    $errors[] = 'Número de cliente inválido.';
}

if ($nombre === '') {
    $errors[] = 'El nombre es obligatorio.';
}

if ($username === '') {
    $errors[] = 'El usuario (username) es obligatorio.';
}

if ($maxAlq < 1) {
    $errors[] = 'El máximo de alquileres debe ser al menos 1.';
}

// Control de acceso: solo admin o el mismo cliente pueden editar
$esAdmin = $usuarioActual->getUsername() === 'admin';
$esElMismo = $usuarioActual->getNumero() === $numero;

if (!$esAdmin && !$esElMismo) {
    $errors[] = 'No tienes permiso para editar este cliente.';
}

// Guardar datos temporales para rellenar el formulario en caso de error
$_SESSION['form_data'] = ['numero' => $numero, 'nombre' => $nombre, 'username' => $username, 'password' => $password, 'maxAlquiler' => $maxAlq];

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: formUpdateCliente.php?numero=' . $numero . '&origen=' . $origen);
    exit;
}

// Obtener videoclub y arrays de sesión
$vc = $_SESSION['videoclub'] ?? null;
$clientesArr = $_SESSION['clientes'] ?? null;

$clienteEncontrado = false;

// Si existe el objeto Videoclub, actualizar sus datos
if (!is_null($vc) && is_object($vc)) {
    $socios = $vc->getSocios();
    foreach ($socios as $socio) {
        if ($socio->getNumero() === $numero) {
            // Actualizar nombre
            $socio->setNombre($nombre);
            
            // Actualizar username y password (si estos métodos existen)
            if (method_exists($socio, 'setUsername')) {
                $socio->setUsername($username);
            }
            if (!empty($password) && method_exists($socio, 'setPassword')) {
                $socio->setPassword($password);
            }
            
            // Actualizar máximo de alquileres si es admin
            if ($esAdmin && method_exists($socio, 'setMaxAlquilerConcurrente')) {
                $socio->setMaxAlquilerConcurrente($maxAlq);
            }
            
            $clienteEncontrado = true;
            break;
        }
    }
    
    // Guardar objeto actualizado en sesión
    $_SESSION['videoclub'] = $vc;
}

// Si también existe el array de clientes en sesión, mantenerlo sincronizado
if (isset($_SESSION['clientes']) && is_array($_SESSION['clientes'])) {
    foreach ($_SESSION['clientes'] as &$cliente) {
        // Verificar si es un objeto Cliente o un array
        if (is_object($cliente)) {
            if ($cliente->getNumero() === $numero) {
                $cliente->setNombre($nombre);
                if (method_exists($cliente, 'setUsername')) {
                    $cliente->setUsername($username);
                }
                if (!empty($password) && method_exists($cliente, 'setPassword')) {
                    $cliente->setPassword($password);
                }
                if ($esAdmin && method_exists($cliente, 'setMaxAlquilerConcurrente')) {
                    $cliente->setMaxAlquilerConcurrente($maxAlq);
                }
                $clienteEncontrado = true;
                break;
            }
        } elseif (is_array($cliente)) {
            if (($cliente['numero'] ?? null) === $numero) {
                $cliente['nombre'] = $nombre;
                $cliente['username'] = $username;
                if (!empty($password)) {
                    $cliente['password'] = $password;
                }
                if ($esAdmin) {
                    $cliente['maxAlquiler'] = $maxAlq;
                }
                $clienteEncontrado = true;
                break;
            }
        }
    }
}

// Si no se encontró el cliente, error
if (!$clienteEncontrado) {
    $_SESSION['form_errors'] = ['Cliente no encontrado.'];
    header('Location: formUpdateCliente.php?numero=' . $numero . '&origen=' . $origen);
    exit;
}

// Limpieza de datos temporales
unset($_SESSION['form_data'], $_SESSION['form_errors']);

// Mensaje flash de éxito
$_SESSION['flash_success'] = 'Cliente actualizado correctamente.';

// Redirigir según origen
$destino = ($origen === 'mainCliente') ? 'mainCliente.php' : 'mainAdmin.php';
header('Location: ' . $destino);
exit;
