<?php
require_once __DIR__ . '/autoload.php';
session_start();

// Recuperar posibles datos previos y errores de la sesión (flash)
$errors = $_SESSION['form_errors'] ?? [];
$data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

// Control de acceso: requiere usuario admin
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
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <h1>Dar de alta cliente</h1>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="createCliente.php" method="post" class="mt-3">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required value="<?= htmlspecialchars($data['nombre'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Usuario (username) *</label>
                <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($data['username'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($data['password'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="maxAlquiler" class="form-label">Máx. alquileres concurrentes</label>
                <input type="number" id="maxAlquiler" name="maxAlquiler" class="form-control" min="1" value="<?= htmlspecialchars($data['maxAlquiler'] ?? 3) ?>">
            </div>

            <button type="submit" class="btn btn-primary">Crear cliente</button>
            <a href="mainAdmin.php" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>
