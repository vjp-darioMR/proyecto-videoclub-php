<?php
// CARGA DE DEPENDENCIAS Y AUTOLOADING
// Incluye el archivo de autoloading para cargar automáticamente todas las clases del proyecto
require_once __DIR__ . '/autoload.php';

// INICIALIZACIÓN DE SESIÓN Y RECUPERACIÓN DE DATOS
// Inicia la sesión para acceder a las variables almacenadas durante el login del usuario
session_start();

// Recupera los datos del usuario autenticado, el objeto Videoclub y los arrays de prueba
// Estos datos se almacenan en la sesión durante el proceso de login
$usuario = $_SESSION['user'] ?? null;           // Objeto del usuario administrador
$vc = $_SESSION['videoclub'] ?? null;           // Objeto Videoclub completo (estructura avanzada)
$clientesArr = $_SESSION['clientes'] ?? null;   // Array asociativo de clientes (estructura básica)
$soportesArr = $_SESSION['soportes'] ?? null;   // Array asociativo de soportes (estructura básica)

// CONTROL DE ACCESO AL PANEL DE ADMINISTRACIÓN
// Verifica que el usuario esté logueado, sea administrador y tenga acceso a los datos necesarios
// Solo los administradores pueden acceder a este panel, y deben tener al menos
// el objeto Videoclub o los arrays de prueba disponibles
if (
    !isset($_SESSION['user']) ||
    $usuario->getUsername() !== 'admin'
    || (is_null($vc) && (is_null($clientesArr) || is_null($soportesArr)))
) {
    header('Location: index.php');  // Redirige al login si no cumple los requisitos
    exit;
}

// PREPARACIÓN DE DATOS PARA LA VISTA
// Obtiene la lista de clientes desde el objeto Videoclub o desde el array de prueba
// Esto permite mostrar los clientes en la interfaz independientemente de la estructura de datos usada
$clientes = [];
if ($vc && method_exists($vc, 'getSocios')) {
    // Si existe el objeto Videoclub y tiene el método getSocios, usa esa fuente de datos
    $clientes = $vc->getSocios();
} elseif (is_array($clientesArr)) {
    // Si no hay Videoclub pero sí el array de clientes, usa ese array
    $clientes = $clientesArr;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <!-- Carga las hojas de estilo de Bootstrap para el diseño responsivo -->
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <!-- Carga los iconos de Bootstrap para los botones y elementos visuales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container mt-5">
        <h1>Panel de Administrador</h1>
        <p class="lead">Bienvenido, <strong><?= htmlspecialchars($usuario->getUsername()) ?></strong>.</p>

        <!-- MENSAJE FLASH DE ÉXITO
             Muestra mensajes de éxito temporales (como después de crear o eliminar un cliente)
             Se elimina de la sesión después de mostrarlo para que no aparezca en recargas -->
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <div class="row">
            <!-- COLUMNA DE CLIENTES (izquierda) -->
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-3">Clientes</h3>
                    <!-- Botón para crear un nuevo cliente -->
                    <a href="formCreateCliente.php" class="btn btn-sm btn-success"> <i class="bi bi-person"> </i>Nuevo cliente</a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php
                    // LISTADO DE CLIENTES
                    // Muestra todos los clientes en tarjetas, con opciones para editar y eliminar
                    // Maneja dos estructuras de datos posibles: objetos Cliente o arrays asociativos

                    if (!is_null($vc)):
                        // MUESTRA CLIENTES DESDE EL OBJETO VIDEOCLUB
                        // Usa la API completa del objeto Videoclub para mostrar información detallada
                        foreach ($vc->getSocios() as $cliente): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- Nombre del cliente y número identificador -->
                                        <h5 class="card-title"><?= htmlspecialchars($cliente->getNombre()) . " (" . $cliente->getNumero() . ")" ?></h5>
                                        <p class="card-text">
                                            <!-- Información básica del cliente -->
                                            <strong>Usuario:</strong> <?= htmlspecialchars($cliente->getUsername()) ?><br>
                                            <strong>Alquileres:</strong> <?= $cliente->getNumSoportesAlquilados() ?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex gap-2">
                                        <!-- Botón para editar el cliente -->
                                        <a href="formUpdateCliente.php?numero=<?= $cliente->getNumero() ?>&origen=mainAdmin" class="btn btn-sm btn-warning flex-grow-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <!-- Formulario para eliminar cliente con confirmación JavaScript -->
                                        <form method="post" action="removeCliente.php" class="flex-grow-1" onsubmit="return confirmarBorrado('<?= htmlspecialchars($cliente->getNombre()) ?>');">
                                            <input type="hidden" name="numero" value="<?= $cliente->getNumero() ?>">
                                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                    elseif (!is_null($clientesArr)):
                        // MUESTRA CLIENTES DESDE EL ARRAY ASOCIATIVO
                        // Usa la estructura básica de array para mostrar información simplificada
                        foreach ($clientesArr as $cliente): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($cliente['nombre']) ?></h5>
                                        <p class="card-text">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($cliente['username']) ?><br>
                                            <strong>Alquileres:</strong> <?= count($cliente['alquileres']) ?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex gap-2">
                                        <!-- Botón para editar el cliente -->
                                        <a href="formUpdateCliente.php?numero=<?= $cliente['numero'] ?? 0 ?>&origen=mainAdmin" class="btn btn-sm btn-warning flex-grow-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <!-- Formulario para eliminar cliente con confirmación JavaScript -->
                                        <form method="post" action="removeCliente.php" class="flex-grow-1" onsubmit="return confirmarBorrado('<?= htmlspecialchars($cliente['nombre']) ?>');">
                                            <input type="hidden" name="numero" value="<?= $cliente['numero'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <!-- COLUMNA DE SOPORTES (derecha) -->
            <div class="col-md-6">
                <h3>Soportes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <?php
                    // LISTADO DE SOPORTES (PRODUCTOS)
                    // Muestra todos los productos disponibles en el videoclub
                    // Indica visualmente si están alquilados o disponibles

                    if (!is_null($vc)):
                        // MUESTRA SOPORTES DESDE EL OBJETO VIDEOCLUB
                        // Usa la API completa para mostrar información detallada de cada producto
                        foreach ($vc->getProductos() as $soporte): ?>
                            <div class="col">
                                <!-- La clase CSS cambia según el estado de alquiler (verde = disponible, rojo = alquilado) -->
                                <div class="card h-100 <?= $soporte->alquilado ? 'border-danger' : 'border-success' ?>">
                                    <div class="card-body">
                                        <!-- Título del soporte/producto -->
                                        <h5 class="card-title"><?= htmlspecialchars($soporte->getTitulo()) ?></h5>
                                        <p class="card-text">
                                            <!-- Información básica del producto -->
                                            <strong>Nº:</strong> <?= $soporte->getNumero() ?><br>
                                            <strong>Precio:</strong> <?= $soporte->getPrecio() ?> €
                                            <!-- Badge que indica el estado de disponibilidad -->
                                            <span class="badge <?= $soporte->alquilado ? 'bg-danger' : 'bg-success' ?>">
                                                <?= $soporte->alquilado ? 'Alquilado' : 'Disponible' ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                    elseif (!is_null($soportesArr)):
                        // MUESTRA SOPORTES DESDE EL ARRAY ASOCIATIVO
                        // Usa la estructura básica de array para mostrar información simplificada
                        foreach ($soportesArr as $soporte): ?>
                            <div class="col">
                                <!-- La clase CSS cambia según el estado de alquiler -->
                                <div class="card h-100 <?= $soporte['alquilado'] ? 'border-danger' : 'border-success' ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($soporte['titulo']) ?></h5>
                                        <p class="card-text">
                                            <strong>Nº:</strong> <?= $soporte['numero'] ?><br>
                                            <strong>Precio:</strong> <?= $soporte['precio'] ?> €
                                            <!-- Badge que indica el estado de disponibilidad -->
                                            <span class="badge <?= $soporte['alquilado'] ? 'bg-danger' : 'bg-success' ?>">
                                                <?= $soporte['alquilado'] ? 'Alquilado' : 'Disponible' ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- FUNCIONES JAVASCRIPT -->
    <script>
        // CONFIRMACIÓN DE ELIMINACIÓN DE CLIENTE
        // Función que muestra un diálogo de confirmación antes de eliminar un cliente
        // Recibe el nombre del cliente para personalizar el mensaje
        // Retorna true si el usuario confirma, false si cancela
        function confirmarBorrado(nombre) {
            return confirm('¿Seguro que deseas eliminar al cliente "' + nombre + '"? Esta acción no se puede deshacer.');
        }
    </script>

    <!-- Carga del JavaScript de Bootstrap para funcionalidades interactivas -->
    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>

</html>