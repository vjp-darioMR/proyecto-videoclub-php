<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin' || !isset($_SESSION['videoclub'])) {
    header('Location: index.php');
    exit;
}
$vc = $_SESSION['videoclub'];
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
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Panel de Administrador</h1>
        <p class="lead">Bienvenido, <strong>admin</strong>.</p>

        <div class="row">
            <div class="col-md-6">
                <h3>Clientes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3">
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
                </div>
            </div>

            <div class="col-md-6">
                <h3>Soportes</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3">
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
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>