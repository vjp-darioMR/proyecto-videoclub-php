<?php
// PROCESO DE CIERRE DE SESIÓN (LOGOUT)

// Este archivo maneja el cierre de sesión del usuario actual,
// eliminando todos los datos de sesión y redirigiendo al formulario de login

// Inicia la sesión para poder acceder y manipular las variables de sesión
// Esto es necesario porque session_destroy() requiere que la sesión esté activa
session_start();

// Destruye completamente la sesión actual, eliminando todas las variables
// almacenadas en $_SESSION (como 'user', 'videoclub', 'soportes', 'clientes')
// Esto asegura que el usuario quede completamente desconectado
session_destroy();

// Redirige al usuario de vuelta a la página de login (index.php)
// para que pueda iniciar sesión nuevamente si lo desea
header('Location: index.php');

// Finaliza la ejecución del script inmediatamente después de la redirección
// para evitar que se ejecute código adicional innecesario
exit;
