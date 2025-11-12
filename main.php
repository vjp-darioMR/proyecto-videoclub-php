<?php
session_start();

// PROTECCIÓN: Si no hay sesión → vuelve al login
if (!isset($_SESSION['usuario'])) {
    header("Location: index2.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Videoclub - Bienvenido</title>
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="main.php"><i class="bi bi-film"></i> Videoclub</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($usuario); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="container py-5 text-center">
        <div class="alert alert-success">
            <h1><i class="bi bi-check-circle"></i> ¡Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1>
            <p>Has iniciado sesión correctamente.</p>
            <a href="logout.php" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-light text-center py-3 mt-auto">
        <p class="mb-0">Videoclub PHP</p>
    </footer>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>