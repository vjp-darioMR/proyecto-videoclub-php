<?php
require_once __DIR__ . '/autoload.php';
session_start();

use Dwes\ProyectoVideoclub\Videoclub;

// Recuperar posibles datos previos y errores de la sesión (flash)
$errors = $_SESSION['form_errors'] ?? [];
$data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

// Control de acceso: requiere usuario logueado
$usuarioActual = $_SESSION['user'] ?? null;
if (!isset($usuarioActual)) {
    header('Location: index.php');
    exit;
}

// Obtener número de cliente a editar
$numeroCliente = (int)($_GET['numero'] ?? 0);
if ($numeroCliente <= 0) {
    header('Location: index.php');
    exit;
}

// Obtener objeto videoclub
$vc = $_SESSION['videoclub'] ?? null;
$clientesArr = $_SESSION['clientes'] ?? null;

$clienteEditar = null;

// Buscar cliente en el objeto Videoclub
if (!is_null($vc) && is_object($vc)) {
    foreach ($vc->getSocios() as $socio) {
        if ($socio->getNumero() === $numeroCliente) {
            $clienteEditar = [
                'numero' => $socio->getNumero(),
                'nombre' => $socio->getNombre(),
                'username' => $socio->getUsername(),
                'password' => $socio->getPassword(),
                'maxAlquiler' => $socio->getMaxAlquilerConcurrente() // Nota: revisar nombre del método
            ];
            break;
        }
    }
} elseif (!is_null($clientesArr) && is_array($clientesArr)) {
    // Buscar en el array de clientes
    foreach ($clientesArr as $cliente) {
        if (($cliente['numero'] ?? null) === $numeroCliente) {
            $clienteEditar = $cliente;
            break;
        }
    }
}

// Si no se encuentra el cliente, redirigir
if (is_null($clienteEditar)) {
    header('Location: index.php');
    exit;
}

// Control de acceso: solo admin o el mismo cliente pueden editar
$esAdmin = $usuarioActual->getUsername() === 'admin';
$esElMismo = $usuarioActual->getNumero() === $numeroCliente;

if (!$esAdmin && !$esElMismo) {
    header('Location: index.php');
    exit;
}

// Preparar datos para el formulario
$formData = !empty($data) ? $data : $clienteEditar;
$origen = $_GET['origen'] ?? 'mainAdmin'; // 'mainAdmin' o 'mainCliente'
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <h1>Editar Cliente</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="updateCliente.php" method="post" class="mt-3">
            <input type="hidden" name="numero" value="<?= htmlspecialchars($formData['numero'] ?? $numeroCliente) ?>">
            <input type="hidden" name="origen" value="<?= htmlspecialchars($origen) ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Usuario (username) *</label>
                <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($formData['username'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($formData['password'] ?? '') ?>">
                <small class="text-muted">Déjalo en blanco si no deseas cambiar la contraseña</small>
            </div>

            <?php if ($esAdmin): ?>
                <div class="mb-3">
                    <label for="maxAlquiler" class="form-label">Máx. alquileres concurrentes</label>
                    <input type="number" id="maxAlquiler" name="maxAlquiler" class="form-control" min="1" value="<?= htmlspecialchars($formData['maxAlquiler'] ?? 3) ?>">
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="<?= $origen === 'mainCliente' ? 'mainCliente.php' : 'mainAdmin.php' ?>" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>
