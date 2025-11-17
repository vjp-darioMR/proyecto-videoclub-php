<?php
session_start();
if (!isset($_SESSION['cliente'])) {
    header('Location: index.php');
    exit;
}
$cliente = $_SESSION['cliente'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Alquileres</title>
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container mt-5">
        <h1>Mis Alquileres</h1>
        <p class="lead">Hola, <strong><?= htmlspecialchars($cliente->getNombre()) ?></strong>.</p>

        <h3>Alquileres Actuales (<?= $cliente->getNumSoportesAlquilados() ?>)</h3>

        <?php if ($cliente->getNumSoportesAlquilados() > 0): ?>
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <?php foreach ($cliente->getAlquileres() as $soporte): ?>
                    <div class="col">
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
            <div class="alert alert-info">No tienes ningún soporte alquilado.</div>
        <?php endif; ?>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>