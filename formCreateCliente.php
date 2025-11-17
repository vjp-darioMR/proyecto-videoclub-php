<?php
// INICIALIZACIÓN DEL FORMULARIO DE ALTA DE CLIENTE
// Este archivo maneja la presentación del formulario para crear un nuevo cliente en el sistema.
// Solo los administradores pueden acceder a esta funcionalidad.

// Carga el archivo de autoloading para acceder a las clases del proyecto
require_once __DIR__ . '/autoload.php';

// Inicia la sesión para acceder a las variables de sesión del usuario autenticado
session_start();

// RECUPERACIÓN DE DATOS DE SESIÓN (FLASH DATA)
// Recupera posibles errores de validación y datos del formulario previos
// que se almacenaron en la sesión durante el procesamiento del formulario.
// Estos datos se usan para mostrar errores y mantener los valores introducidos.
$errors = $_SESSION['form_errors'] ?? [];
$data = $_SESSION['form_data'] ?? [];

// Elimina los datos flash de la sesión para que no persistan en futuras peticiones
unset($_SESSION['form_errors'], $_SESSION['form_data']);

// CONTROL DE ACCESO: VERIFICACIÓN DE PERMISOS DE ADMINISTRADOR
// Verifica que el usuario esté logueado y tenga permisos de administrador.
// Solo los administradores pueden crear nuevos clientes en el sistema.
// Si no cumple los requisitos, redirige al formulario de login.
$usuario = $_SESSION['user'] ?? null;
if (!isset($usuario) || $usuario->getUsername() !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alta Cliente</title>
    <!-- Carga las hojas de estilo de Bootstrap y personalizadas -->
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <!-- Título principal de la página -->
        <h1>Dar de alta cliente</h1>
        
        <!-- Mostrar errores de validación si existen -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario de creación de nuevo cliente -->
        <form action="createCliente.php" method="post" class="mt-3">
            <!-- Campo para el nombre completo (requerido) -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required value="<?= htmlspecialchars($data['nombre'] ?? '') ?>">
            </div>

            <!-- Campo para el nombre de usuario (requerido) -->
            <div class="mb-3">
                <label for="username" class="form-label">Usuario (username) *</label>
                <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($data['username'] ?? '') ?>">
            </div>

            <!-- Campo para la contraseña (requerido para crear cuenta) -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($data['password'] ?? '') ?>">
            </div>

            <!-- Campo para máximo alquileres concurrentes (opcional, valor por defecto 3) -->
            <div class="mb-3">
                <label for="maxAlquiler" class="form-label">Máx. alquileres concurrentes</label>
                <input type="number" id="maxAlquiler" name="maxAlquiler" class="form-control" min="1" value="<?= htmlspecialchars($data['maxAlquiler'] ?? 3) ?>">
            </div>

            <!-- Botones de acción: crear cliente o cancelar -->
            <button type="submit" class="btn btn-primary">Crear cliente</button>
            <a href="mainAdmin.php" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <!-- Carga el JavaScript de Bootstrap para funcionalidades interactivas -->
    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>
