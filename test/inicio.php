<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto VideoClub - PHP</title>
    <!-- Bootstrap y Boostrap icons -->
    <link rel="stylesheet" href="../vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../vendor/styles/custom.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Barra de navegación principal -->
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-film"></i> Videoclub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02"
                aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html"><i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="bi bi-filetype-php"></i> Pruebas</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="inicio.php">Inicio.php</a>
                            <a class="dropdown-item" href="inicio2.php">Inicio2.php</a>
                            <a class="dropdown-item" href="inicio3.php">Inicio3.php</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="col">
            <?php
            // Incluimos el cargador automático de clases
            include_once('../autoload.php');
            use Dwes\ProyectoVideoclub\CintaVideo;
            use Dwes\ProyectoVideoclub\Dvd;
            use Dwes\ProyectoVideoclub\Juego;

            // Instanciamos los soportes de prueba
            $miCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 107);
            $miDvd = new Dvd("Origen", 24, 15, "Español, Ingles, Frances", "16:9");
            $mijuego1 = new Juego("The Last of Us", 26, 49.99, "PS4", 1, 1);
            ?>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <!-- Título de la sección de soportes -->
                        <h2 class="mt-4 mb-4"><i class="bi bi-bag"></i> Soportes de prueba</h2>
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <?php
                            // Mostramos cada soporte como tarjeta Bootstrap
                            $miCinta->muestraResumen();
                            $miDvd->muestraResumen();
                            $mijuego1->muestraResumen();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pie de página -->
    <footer class="bg-dark text-light text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">
                Proyecto Videoclub PHP - Darío Muñoz Rodríguez / Yago García Alonso
            </p>
            <p class="mb-0">
                <small>Diseño realizado por Darío, con Bootstrap v5.3.8 y el tema <a class="link link-primary" href="https://bootswatch.com/brite/">Brite</a></small>
            </p>
        </div>
    </footer>
    <!-- Bootstrap Bundle with Popper -->
    <script src="../vendor/scripts/bootstrap.min.js"></script>
</body>
</html>