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
if (
    !isset($_SESSION['user']) || 
    $usuario->getUsername() !== 'admin'
    || (is_null($vc) && (is_null($clientesArr) || is_null($soportesArr)))) {
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

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-3">Clientes</h3>
                    <a href="formCreateCliente.php" class="btn btn-sm btn-success">Nuevo cliente</a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php
                    // Preferir el objeto Videoclub para listar socios; si no existe, usar el array de sesión
                    if (!is_null($vc)):
                        foreach ($vc->getSocios() as $cliente): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($cliente->getNombre()) ?></h5>
                                        <p class="card-text">
                                            <strong>Usuario:</strong> <?= htmlspecialchars($cliente->getUsername()) ?><br>
                                            <strong>Alquileres:</strong> <?= $cliente->getNumSoportesAlquilados() ?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="formUpdateCliente.php?numero=<?= $cliente->getNumero() ?>&origen=mainAdmin" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                    elseif (!is_null($clientesArr)):
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
                                    <div class="card-footer bg-transparent">
                                        <a href="formUpdateCliente.php?numero=<?= $cliente['numero'] ?? 0 ?>&origen=mainAdmin" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <h3>Soportes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <?php
                    // Preferir el objeto Videoclub para listar productos; si no existe, usar el array de sesión
                    if (!is_null($vc)):
                        foreach ($vc->getProductos() as $soporte): ?>
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
                        <?php endforeach;
                    elseif (!is_null($soportesArr)):
                        foreach ($soportesArr as $soporte): ?>
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
                    <?php endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>

</html>