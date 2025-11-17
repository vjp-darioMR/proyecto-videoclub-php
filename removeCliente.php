<?php
// PROCESAMIENTO DE ELIMINACIÓN DE CLIENTES - PANEL DE ADMINISTRACIÓN

// Este archivo maneja la eliminación de clientes del sistema videoclub.
// Solo los administradores pueden acceder a esta funcionalidad.
// El proceso incluye validaciones de seguridad, búsqueda del cliente y eliminación
// tanto del objeto Videoclub como de los arrays de respaldo en la sesión.
// Se implementa manejo de errores y mensajes flash para feedback al usuario.

// CARGA DE DEPENDENCIAS Y AUTOLOADING
// Incluye el archivo de autoloading para cargar automáticamente todas las clases del proyecto
require_once "autoload.php";

// INICIALIZACIÓN DE SESIÓN Y CONFIGURACIÓN
// Inicia la sesión para acceder a las variables almacenadas durante el login del usuario
session_start();

// Importa la clase Videoclub para trabajar con el objeto principal del sistema
use Dwes\ProyectoVideoclub\Videoclub;

// CONTROL DE ACCESO AL PANEL DE ADMINISTRACIÓN
// Verifica que el usuario esté logueado y tenga permisos de administrador
// Solo los administradores pueden eliminar clientes del sistema
// Si no cumple los requisitos, redirige al formulario de login
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getUsername() !== 'admin') {
    header('Location: index.php');
    exit;
}

// VALIDACIÓN DEL MÉTODO HTTP
// Solo permite solicitudes POST para eliminar clientes (seguridad CSRF básica)
// Las eliminaciones deben hacerse mediante formularios POST, no GET
// Si se accede con otro método, redirige al panel de administración
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mainAdmin.php');
    exit;
}

// OBTENCIÓN Y VALIDACIÓN DE PARÁMETROS DE ENTRADA
// Recupera el número del cliente a eliminar desde el formulario POST
// Convierte a entero para evitar inyección y valida que sea un número positivo
$numero = isset($_POST['numero']) ? (int)$_POST['numero'] : 0;

// VALIDACIÓN ADICIONAL DEL NÚMERO DE CLIENTE
// Verifica que el número sea válido y no sea el administrador (socio 1)
// El administrador no puede eliminarse a sí mismo por seguridad
if ($numero <= 0 || $numero === 1) { // No permitir eliminar admin (socio 1)
    $_SESSION['flash_error'] = 'No se puede eliminar este cliente.';
    header('Location: mainAdmin.php');
    exit;
}

// PROCESAMIENTO DE ELIMINACIÓN DEL CLIENTE
// Verifica que exista el objeto Videoclub en la sesión y sea de la clase correcta
// Si existe, procede con la eliminación del cliente especificado
if (isset($_SESSION['videoclub']) && $_SESSION['videoclub'] instanceof Videoclub) {
    $vc = $_SESSION['videoclub'];
    $socios = $vc->getSocios();
    
    // BÚSQUEDA DEL CLIENTE A ELIMINAR
    // Recorre el array de socios buscando el cliente con el número especificado
    // Una vez encontrado, lo elimina del array y marca como encontrado
    $encontrado = false;
    foreach ($socios as $i => $socio) {
        if ($socio->getNumero() === $numero) {
            array_splice($socios, $i, 1);
            $encontrado = true;
            break;
        }
    }
    
    // ACTUALIZACIÓN DEL OBJETO VIDEOCLUB
    // Si el cliente fue encontrado y eliminado, actualiza el objeto Videoclub
    // Usa el método setSocios si existe, o reflexión para acceder a propiedades privadas
    if ($encontrado) {
        if (method_exists($vc, 'setSocios')) {
            $vc->setSocios($socios);
        } else {
            // ALTERNATIVA: ACCESO DIRECTO MEDIANTE REFLEXIÓN
            // Si no hay método setSocios, usa reflexión para modificar la propiedad privada
            // Esto es necesario para mantener la integridad del objeto cuando la API es limitada
            $reflection = new ReflectionClass($vc);
            $property = $reflection->getProperty('socios');
            $property->setAccessible(true);
            $property->setValue($vc, $socios);
        }
        // ACTUALIZACIÓN DE LA SESIÓN
        // Guarda el objeto Videoclub modificado en la sesión
        // También actualiza el array de clientes para consistencia
        $_SESSION['videoclub'] = $vc;
        $_SESSION['clientes'] = $socios;
        $_SESSION['flash_success'] = 'Cliente eliminado correctamente.';
    } else {
        $_SESSION['flash_error'] = 'Cliente no encontrado.';
    }
} else {
    $_SESSION['flash_error'] = 'Error: Videoclub no disponible.';
}

// REDIRECCIÓN FINAL AL PANEL DE ADMINISTRACIÓN
// Después de procesar la eliminación (éxito o error), redirige al panel de administración
// Los mensajes flash se mostrarán en la página de destino para informar al usuario
header('Location: mainAdmin.php');
exit;