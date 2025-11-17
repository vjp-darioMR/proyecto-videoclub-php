<?php
// INICIALIZACIÓN DEL FORMULARIO DE EDICIÓN DE CLIENTE
// Este archivo maneja la presentación del formulario para editar los datos de un cliente existente.
// Incluye validaciones de acceso, recuperación de datos y renderizado del formulario HTML.

// Carga el archivo de autoloading para acceder a las clases del proyecto
require_once __DIR__ . '/autoload.php';

// Inicia la sesión para acceder a las variables de sesión del usuario autenticado
session_start();

// Importa la clase Videoclub necesaria para acceder a los datos de clientes
use Dwes\ProyectoVideoclub\Videoclub;

// RECUPERACIÓN DE DATOS DE SESIÓN (FLASH DATA)
// Recupera posibles errores de validación y datos del formulario previos
// que se almacenaron en la sesión durante el procesamiento del formulario.
// Estos datos se usan para mostrar errores y mantener los valores introducidos.
$errors = $_SESSION['form_errors'] ?? [];
$data = $_SESSION['form_data'] ?? [];

// Elimina los datos flash de la sesión para que no persistan en futuras peticiones
unset($_SESSION['form_errors'], $_SESSION['form_data']);

// CONTROL DE ACCESO: VERIFICACIÓN DE USUARIO AUTENTICADO
// Verifica que el usuario esté logueado antes de permitir el acceso al formulario.
// Si no hay usuario en la sesión, redirige al formulario de login.
$usuarioActual = $_SESSION['user'] ?? null;
if (!isset($usuarioActual)) {
    header('Location: index.php');
    exit;
}

// OBTENCIÓN DEL NÚMERO DE CLIENTE A EDITAR
// Recupera el parámetro 'numero' de la URL que indica qué cliente se va a editar.
// Si no se proporciona o es inválido, redirige al inicio.
$numeroCliente = (int)($_GET['numero'] ?? 0);
if ($numeroCliente <= 0) {
    header('Location: index.php');
    exit;
}

// RECUPERACIÓN DE DATOS DEL VIDEOCLUB Y CLIENTES
// Obtiene la instancia del videoclub y el array de clientes almacenados en la sesión.
// Estos datos se usan para buscar y mostrar la información del cliente a editar.
$vc = $_SESSION['videoclub'] ?? null;
$clientesArr = $_SESSION['clientes'] ?? null;

// Variable para almacenar los datos del cliente que se va a editar
$clienteEditar = null;

// BÚSQUEDA DEL CLIENTE A EDITAR
// Busca el cliente por su número en el objeto Videoclub o en el array de clientes.
// Primero intenta buscar en el objeto Videoclub (fuente primaria de datos),
// luego en el array de clientes si no se encuentra.
if (!is_null($vc) && is_object($vc)) {
    // Búsqueda en el objeto Videoclub: recorre todos los socios
    foreach ($vc->getSocios() as $socio) {
        if ($socio->getNumero() === $numeroCliente) {
            // Si encuentra el cliente, extrae sus datos en un array asociativo
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
    // Búsqueda alternativa en el array de clientes de la sesión
    foreach ($clientesArr as $cliente) {
        if (($cliente['numero'] ?? null) === $numeroCliente) {
            $clienteEditar = $cliente;
            break;
        }
    }
}

// VALIDACIÓN DE EXISTENCIA DEL CLIENTE
// Si no se encuentra el cliente con el número especificado, redirige al inicio.
// Esto previene errores al intentar editar un cliente inexistente.
if (is_null($clienteEditar)) {
    header('Location: index.php');
    exit;
}


// CONTROL DE ACCESO AVANZADO: PERMISOS DE EDICIÓN

// Verifica que el usuario tenga permisos para editar este cliente específico.
// Solo el administrador o el propio cliente pueden editar sus datos.
$esAdmin = $usuarioActual->getUsername() === 'admin';
$esElMismo = $usuarioActual->getNumero() === $numeroCliente;

if (!$esAdmin && !$esElMismo) {
    header('Location: index.php');
    exit;
}


// PREPARACIÓN DE DATOS PARA EL FORMULARIO

// Prepara los datos que se mostrarán en el formulario.
// Si hay datos previos de una validación fallida, los usa; sino, usa los datos del cliente.
$formData = !empty($data) ? $data : $clienteEditar;

// Determina el origen de la petición para saber a dónde redirigir después
$origen = $_GET['origen'] ?? 'mainAdmin'; // 'mainAdmin' o 'mainCliente'
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Cliente</title>
    <!-- Carga las hojas de estilo de Bootstrap y personalizadas -->
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <!-- Título principal de la página -->
        <h1>Editar Cliente</h1>
        
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

    <!-- Carga el JavaScript de Bootstrap para funcionalidades interactivas -->
    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>
