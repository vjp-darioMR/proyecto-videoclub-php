<?php
require_once __DIR__ . '/autoload.php';


// Iniciamos sesión y recuperamos los datos que podrían haber sido
// guardados durante el login. Soportes y clientes se guardan como
// arrays asociativos para las páginas de administración en esta fase
// (más adelante vendrán de la base de datos).
session_start();
$usuario = $_SESSION['user'] ?? null;
$vc = $_SESSION['videoclub'] ?? null; // objeto Videoclub (fallback)
$clientesArr = $_SESSION['clientes'] ?? null; // array asociativo de clientes (prueba)
$soportesArr = $_SESSION['soportes'] ?? null; // array asociativo de soportes (prueba)

// Control de acceso: requiere usuario logueado que sea 'admin' y, al
// menos, disponer del objeto Videoclub o de los arrays de prueba.
if (!isset($_SESSION['user']) || $usuario->getNombre() !== 'admin' || (is_null($vc) && (is_null($clientesArr) || is_null($soportesArr)))) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container mt-5">
        <h1>Panel de Administrador</h1>
        <p class="lead">Bienvenido, <strong><?= htmlspecialchars($usuario->getUsername()) ?></strong>.</p>

        <div class="row">
            <div class="col-md-6">
                <h3>Clientes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php // Si existen arrays de prueba en sesión, los usamos directamente
                    if (!is_null($clientesArr)): ?>
                        <?php foreach ($clientesArr as $cliente): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($cliente['nombre']) ?></h5>
                                        <p class="card-text">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($cliente['username']) ?><br>
                                            <strong>Alquileres:</strong> <?= count($cliente['alquileres']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: // Fallback: usar método del objeto Videoclub para listar socios ?>
                        <?php foreach ($vc->getSocios() as $cliente): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($cliente->getNombre()) ?></h5>
                                        <p class="card-text">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($cliente->getUsername()) ?><br>
                                            <strong>Alquileres:</strong> <?= $cliente->getNumSoportesAlquilados() ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h3>Soportes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <?php // Si existen arrays de soportes en sesión, los usamos para renderizar
                    if (!is_null($soportesArr)): ?>
                        <?php foreach ($soportesArr as $soporte): ?>
                            <div class="col">
                                <div class="card h-100 <?= $soporte['alquilado'] ? 'border-danger' : 'border-success' ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($soporte['titulo']) ?></h5>
                                        <p class="card-text">
                                            <strong>Nº:</strong> <?= $soporte['numero'] ?><br>
                                            <strong>Precio:</strong> <?= $soporte['precio'] ?> €
                                            <span class="badge <?= $soporte['alquilado'] ? 'bg-danger' : 'bg-success' ?>">
                                                <?= $soporte['alquilado'] ? 'Alquilado' : 'Disponible' ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: // Fallback: usar el array de productos del objeto Videoclub ?>
                        <?php foreach ($vc->getProductos() as $soporte): ?>
                            <div class="col">
                                <div class="card h-100 <?= $soporte->alquilado ? 'border-danger' : 'border-success' ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($soporte->getTitulo()) ?></h5>
                                        <p class="card-text">
                                            <strong>Nº:</strong> <?= $soporte->getNumero() ?><br>
                                            <strong>Precio:</strong> <?= $soporte->getPrecio() ?> €
                                            <span class="badge <?= $soporte->alquilado ? 'bg-danger' : 'bg-success' ?>">
                                                <?= $soporte->alquilado ? 'Alquilado' : 'Disponible' ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>

</html>