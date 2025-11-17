<?php
// PROCESAMIENTO DE CREACIÓN DE NUEVO CLIENTE
// Este archivo maneja la lógica de servidor para crear un nuevo cliente en el sistema.
// Recibe los datos del formulario, los valida y los almacena en la sesión.

// Carga el archivo de autoloading para acceder a las clases del proyecto
require_once __DIR__ . '/autoload.php';

// Inicia la sesión para acceder y modificar las variables de sesión
session_start();

// CONTROL DE ACCESO: VERIFICACIÓN DE PERMISOS DE ADMINISTRADOR
// Verifica que el usuario esté logueado y tenga permisos de administrador.
// Solo los administradores pueden crear nuevos clientes en el sistema.
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getUsername() !== 'admin') {
    header('Location: index.php');
    exit;
}

// VALIDACIÓN DEL MÉTODO HTTP
// Solo acepta peticiones POST para evitar accesos directos por URL.
// Si se accede con GET u otro método, redirige al formulario.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: formCreateCliente.php');
    exit;
}

// RECUPERACIÓN Y VALIDACIÓN DE DATOS DEL FORMULARIO
// Obtiene los datos enviados desde el formulario y realiza validaciones básicas.
// Los datos se limpian y convierten a los tipos apropiados.

// Recupera y limpia los campos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$maxAlq = isset($_POST['maxAlquiler']) ? (int)$_POST['maxAlquiler'] : 3;

// VALIDACIONES DE DATOS
// Array para almacenar los errores de validación encontrados
$errors = [];

// Verifica que el nombre no esté vacío
if ($nombre === '') {
    $errors[] = 'El nombre es obligatorio.';
}

// Verifica que el username no esté vacío
if ($username === '') {
    $errors[] = 'El usuario (username) es obligatorio.';
}

// Verifica que el máximo de alquileres sea al menos 1
if ($maxAlq < 1) {
    $errors[] = 'El máximo de alquileres debe ser al menos 1.';
}

// ALMACENAMIENTO TEMPORAL DE DATOS PARA REESTRUCTURACIÓN
// Guarda los datos del formulario en la sesión para poder rellenar el formulario
// en caso de que haya errores de validación (evita que el usuario tenga que
// volver a escribir todo)
$_SESSION['form_data'] = ['nombre' => $nombre, 'username' => $username, 'password' => $password, 'maxAlquiler' => $maxAlq];

// MANEJO DE ERRORES DE VALIDACIÓN
// Si se encontraron errores, los guarda en la sesión y redirige al formulario
// para que se muestren los errores y se mantengan los datos introducidos
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: formCreateCliente.php');
    exit;
}

// PROCESAMIENTO DE CREACIÓN DEL CLIENTE
// El código maneja dos estructuras de datos posibles:
// 1. Objeto Videoclub (estructura completa con métodos)
// 2. Array simple de clientes (estructura básica)

// Si existe el objeto Videoclub en la sesión, usa su API completa
if (isset($_SESSION['videoclub']) && is_object($_SESSION['videoclub'])) {
    $vc = $_SESSION['videoclub'];

    // Sincroniza el contador interno de socios con los socios existentes
    // Esto asegura que el contador esté correcto antes de agregar uno nuevo
    $socios = $vc->getSocios();
    $vc->setSocios($socios); // Reinicia numSocios al número correcto

    // Usa el método incluirSocio del Videoclub para agregar el nuevo cliente
    // Este método mantiene los contadores internos y la integridad de los datos
    $vc->incluirSocio($nombre, $maxAlq, $username, $password);

    // Guarda el objeto Videoclub actualizado en la sesión
    $_SESSION['videoclub'] = $vc;

    // SINCRONIZACIÓN CON ARRAY DE CLIENTES (PARA mainAdmin.php)
    // Si también existe el array de clientes en la sesión (usado por mainAdmin),
    // mantiene la sincronización agregando el nuevo cliente al array
    if (isset($_SESSION['clientes']) && is_array($_SESSION['clientes'])) {
        $socios = $vc->getSocios();
        $ultimo = end($socios); // Obtiene el último socio agregado
        if ($ultimo) {
            // Agrega el cliente al array con la estructura esperada
            $_SESSION['clientes'][] = [
                'numero' => $ultimo->getNumero(),
                'nombre' => $ultimo->getNombre(),
                'username' => $ultimo->getUsername(),
                'password' => $ultimo->getPassword(),
                'alquileres' => [] // Array vacío para alquileres futuros
            ];
        }
    }
} else {
    // ESTRUCTURA ALTERNATIVA: ARRAY SIMPLE DE CLIENTES
    // Si no existe el objeto Videoclub, usa la estructura de array simple
    // Esta es una estructura básica usada principalmente en mainAdmin.php

    // Crea un nuevo cliente con la estructura de array simple
    $nuevo = [
        'nombre' => $nombre,
        'username' => $username,
        'password' => $password,
        'alquileres' => [] // Array vacío para alquileres futuros
    ];

    // Asegura que el array de clientes existe en la sesión
    if (!isset($_SESSION['clientes']) || !is_array($_SESSION['clientes'])) {
        $_SESSION['clientes'] = [];
    }

    // Agrega el nuevo cliente al array
    $_SESSION['clientes'][] = $nuevo;
}

// LIMPIEZA DE DATOS TEMPORALES
// Elimina los datos temporales del formulario y errores de la sesión
// ya que la creación fue exitosa y no se necesitan más
unset($_SESSION['form_data'], $_SESSION['form_errors']);

// ASEGURAMIENTO DE ESTRUCTURAS DE DATOS COMPLETAS
// Asegura que también existen los arrays de soportes en la sesión
// Esto previene errores en otras partes de la aplicación que dependen de estos arrays
if (!isset($_SESSION['soportes']) || !is_array($_SESSION['soportes'])) {
    $_SESSION['soportes'] = [];
}

// MENSAJE DE ÉXITO
// Establece un mensaje flash de éxito para mostrar en la página siguiente
$_SESSION['flash_success'] = 'Cliente creado correctamente.';

// REDIRECCIÓN AL PANEL DE ADMINISTRACIÓN
// Redirige al panel de administración para que el usuario pueda ver
// el cliente recién creado en la lista
header('Location: mainAdmin.php');
exit;
