<?php
// PANEL DE CLIENTE - VISUALIZACIÓN DE ALQUILERES PERSONALES
// Este archivo muestra el panel personal del cliente logueado.
// Permite visualizar los soportes actualmente alquilados por el cliente.
// Incluye información del cliente, enlace para editar perfil y listado de alquileres.
// Solo los clientes autenticados pueden acceder a esta página.

// CARGA DE DEPENDENCIAS Y AUTOLOADING
// Incluye el archivo de autoloading para cargar automáticamente todas las clases del proyecto
// Esto permite usar las clases Cliente, Soporte, etc. sin require adicionales
require_once __DIR__ . '/autoload.php';

// INICIALIZACIÓN DE SESIÓN Y CONTROL DE ACCESO
// Inicia la sesión para acceder a las variables almacenadas durante el login
// Verifica que el usuario esté autenticado; si no, redirige al formulario de login
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// RECUPERACIÓN DE DATOS DEL CLIENTE AUTENTICADO
// Obtiene el objeto Cliente desde la sesión (almacenado durante el login)
// Este objeto contiene toda la información del cliente y sus alquileres actuales
$cliente = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Alquileres</title>
    <!-- CARGA DE HOJAS DE ESTILO
         Bootstrap para diseño responsivo y componentes UI
         Estilos personalizados para el tema del videoclub
         Iconos de Bootstrap para elementos visuales -->
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container mt-5">
        <!-- CABECERA DEL PANEL DE CLIENTE
             Muestra el título de la página, saludo personalizado con nombre y número del cliente
             Incluye un botón para editar el perfil del cliente -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Mis Alquileres</h1>
                <p class="lead mb-0">Hola, <strong><?= htmlspecialchars($cliente->getNombre())." (".$cliente->getNumero().")" ?></strong>.</p>
            </div>
            <a href="formUpdateCliente.php?numero=<?= $cliente->getNumero() ?>&origen=mainCliente" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar perfil
            </a>
        </div>

        <!-- SECCIÓN DE ALQUILERES ACTUALES
             Muestra el contador de alquileres actuales vs máximo permitido
             Lista todos los soportes alquilados o mensaje si no hay ninguno -->
        <h3>Alquileres Actuales (<?= $cliente->getNumSoportesAlquilados()."/".$cliente->getMaxAlquilerConcurrente() ?>)</h3>

        <!-- CONDICIONAL: MOSTRAR ALQUILERES SI EXISTEN -->
        <?php if ($cliente->getNumSoportesAlquilados() > 0): ?>
            <!-- GRID RESPONSIVO DE SOPORTES ALQUILADOS
                 Muestra cada soporte alquilado en una tarjeta con información básica
                 Usa Bootstrap para layout responsivo (1 columna en móvil, 3 en desktop) -->
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <?php foreach ($cliente->getAlquileres() as $soporte): ?>
                    <div class="col">
                        <!-- TARJETA DE SOPORTE ALQUILADO
                             Muestra título, número, precio y estado de alquiler
                             Borde verde indica que está alquilado por este cliente -->
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($soporte->getTitulo()) ?></h5>
                                <p class="card-text">
                                    <strong>Nº:</strong> <?= $soporte->getNumero() ?><br>
                                    <strong>Precio:</strong> <?= $soporte->getPrecio() ?> €
                                </p>
                                <span class="badge bg-success">Alquilado</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- MENSAJE CUANDO NO HAY ALQUILERES
                 Muestra una alerta informativa si el cliente no tiene soportes alquilados -->
            <div class="alert alert-info">No tienes ningún soporte alquilado.</div>
        <?php endif; ?>
    </div>

    <!-- CARGA DE JAVASCRIPT DE BOOTSTRAP
         Necesario para funcionalidades interactivas de Bootstrap (aunque no se usan en esta página) -->
    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>