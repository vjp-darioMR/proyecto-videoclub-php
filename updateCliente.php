<?php

// PROCESAMIENTO DE ACTUALIZACIÓN DE CLIENTES

// Este archivo maneja la actualización de información de clientes en el sistema videoclub.
// Procesa datos enviados desde el formulario de edición, realiza validaciones,
// verifica permisos de acceso y actualiza tanto el objeto Videoclub como los arrays de respaldo.
// Soporta tanto estructuras de objetos como arrays asociativos para flexibilidad.
// Incluye manejo de errores, mensajes flash y redirección según el origen de la solicitud.

// CARGA DE DEPENDENCIAS Y AUTOLOADING
// Incluye el archivo de autoloading para cargar automáticamente todas las clases del proyecto
require_once __DIR__ . '/autoload.php';

// INICIALIZACIÓN DE SESIÓN
// Inicia la sesión para acceder a las variables almacenadas durante el login del usuario
session_start();

// CONTROL DE ACCESO BÁSICO
// Verifica que el usuario esté autenticado en la sesión
// Si no hay usuario logueado, redirige al formulario de login
$usuarioActual = $_SESSION['user'] ?? null;
if (!isset($usuarioActual)) {
    header('Location: index.php');
    exit;
}

// VALIDACIÓN DEL MÉTODO HTTP
// Solo permite solicitudes POST para actualizar clientes (seguridad CSRF básica)
// Las actualizaciones deben hacerse mediante formularios POST, no GET
// Si se accede con otro método, redirige al panel de administración
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mainAdmin.php');
    exit;
}

// OBTENCIÓN Y PROCESAMIENTO DE DATOS DEL FORMULARIO
// Recupera todos los campos enviados desde el formulario de edición de cliente
// Aplica saneamiento básico (trim, conversión a tipos apropiados)
// Establece valores por defecto cuando no se proporcionan
$numero = (int)($_POST['numero'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$maxAlq = isset($_POST['maxAlquiler']) ? (int)$_POST['maxAlquiler'] : 3;
$origen = $_POST['origen'] ?? 'mainAdmin';

// VALIDACIONES DE DATOS DE ENTRADA
// Realiza validaciones básicas de los datos proporcionados
// Acumula errores en un array para mostrarlos todos juntos al usuario
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

// CONTROL DE PERMISOS DE ACCESO
// Determina si el usuario actual es administrador o el mismo cliente que se está editando
// Solo administradores pueden editar cualquier cliente, los clientes normales solo pueden editarse a sí mismos
$esAdmin = $usuarioActual->getUsername() === 'admin';
$esElMismo = $usuarioActual->getNumero() === $numero;

if (!$esAdmin && !$esElMismo) {
    $errors[] = 'No tienes permiso para editar este cliente.';
}

// ALMACENAMIENTO TEMPORAL DE DATOS PARA REESTRUCTURACIÓN DEL FORMULARIO
// Guarda los datos del formulario en la sesión para rellenar automáticamente
// el formulario en caso de errores de validación (UX mejorada)
$_SESSION['form_data'] = ['numero' => $numero, 'nombre' => $nombre, 'username' => $username, 'password' => $password, 'maxAlquiler' => $maxAlq];

// MANEJO DE ERRORES DE VALIDACIÓN
// Si hay errores, los guarda en la sesión y redirige de vuelta al formulario
// El formulario mostrará los errores y rellenará los campos con los datos temporales
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: formUpdateCliente.php?numero=' . $numero . '&origen=' . $origen);
    exit;
}

// RECUPERACIÓN DE DATOS DE SESIÓN PARA ACTUALIZACIÓN
// Obtiene el objeto Videoclub y el array de clientes desde la sesión
// Estos datos se mantienen sincronizados durante la sesión del usuario
$vc = $_SESSION['videoclub'] ?? null;
$clientesArr = $_SESSION['clientes'] ?? null;

$clienteEncontrado = false;

// ACTUALIZACIÓN DEL OBJETO VIDEOCLUB (ESTRUCTURA AVANZADA)
// Si existe el objeto Videoclub en la sesión, busca y actualiza el cliente correspondiente
// Actualiza nombre, username, password (si se proporciona) y máximo de alquileres (solo admin)
if (!is_null($vc) && is_object($vc)) {
    $socios = $vc->getSocios();
    foreach ($socios as $socio) {
        if ($socio->getNumero() === $numero) {
            // Actualizar nombre (siempre disponible)
            $socio->setNombre($nombre);
            
            // Actualizar username y password solo si los métodos existen en la clase
            if (method_exists($socio, 'setUsername')) {
                $socio->setUsername($username);
            }
            if (!empty($password) && method_exists($socio, 'setPassword')) {
                $socio->setPassword($password);
            }
            
            // Actualizar máximo de alquileres solo si es administrador y el método existe
            if ($esAdmin && method_exists($socio, 'setMaxAlquilerConcurrente')) {
                $socio->setMaxAlquilerConcurrente($maxAlq);
            }
            
            $clienteEncontrado = true;
            break;
        }
    }
    
    // GUARDAR OBJETO VIDEOCLUB ACTUALIZADO EN SESIÓN
    $_SESSION['videoclub'] = $vc;
}

// SINCRONIZACIÓN DEL ARRAY DE CLIENTES EN SESIÓN
// Si existe el array de clientes en la sesión, lo mantiene sincronizado con el objeto Videoclub
// Maneja tanto objetos Cliente como arrays asociativos para compatibilidad
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

// MANEJO DE ERROR: CLIENTE NO ENCONTRADO
// Si después de buscar en ambas estructuras no se encontró el cliente, muestra error
// Redirige de vuelta al formulario con mensaje de error
if (!$clienteEncontrado) {
    $_SESSION['form_errors'] = ['Cliente no encontrado.'];
    header('Location: formUpdateCliente.php?numero=' . $numero . '&origen=' . $origen);
    exit;
}

// LIMPIEZA DE DATOS TEMPORALES
// Elimina los datos temporales del formulario y errores de la sesión
// Ya no son necesarios después de una actualización exitosa
unset($_SESSION['form_data'], $_SESSION['form_errors']);

// MENSAJE FLASH DE ÉXITO
// Establece un mensaje de confirmación que se mostrará en la página de destino
$_SESSION['flash_success'] = 'Cliente actualizado correctamente.';

// REDIRECCIÓN SEGÚN ORIGEN DE LA SOLICITUD
// Redirige al usuario a la página desde donde vino (admin o cliente)
// Esto proporciona una mejor experiencia de navegación
$destino = ($origen === 'mainCliente') ? 'mainCliente.php' : 'mainAdmin.php';
header('Location: ' . $destino);
exit;
