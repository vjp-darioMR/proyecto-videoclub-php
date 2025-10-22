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
                        <a class="nav-link" href="../index.php"><i class="bi bi-house"></i> Inicio
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
            // Requerimos el autoload para cargar las clases automáticamente
            require_once("../autoload.php");
            // Usamos el espacio de nombres principal del videoclub
            use Dwes\ProyectoVideoclub\Videoclub;

            // Creamos el videoclub
            $vc = new Videoclub("Severo 8A");
            // Incluimos varios productos de prueba usando encadenamiento
            $vc->incluirJuego("God of War", 19.99, "PS4", 1, 1)
                ->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1)
                ->incluirDvd("Torrente", 4.5, "es", "16:9")
                ->incluirDvd("Origen", 4.5, "es,en,fr", "16:9")
                ->incluirDvd("El Imperio Contraataca", 3, "es,en", "16:9")
                ->incluirCintaVideo("Los cazafantasmas", 3.5, 107)
                ->incluirCintaVideo("El nombre de la Rosa", 1.5, 140);

            ?>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <!-- Sección de productos disponibles -->
                        <h2 class="mt-4 mb-4"><i class="bi bi-bag"></i> Productos disponibles</h2>
                        <!-- Grid con bootstrap para mostrar varias tarjetas -->
                        <div class="row row-cols-1 row-cols-md-4 g-4">
                            <?php
                            // Listamos los productos (se renderizan con HTML y Bootstrap)
                            $vc->listarProductos();
                            ?>
                        </div>
                        <hr>
                        <?php
                        // Creamos algunos socios usando encadenamiento
                        $vc->incluirSocio("Amancio Ortega")->incluirSocio("Pablo Picasso", 2);
                        // Alquilamos productos a los socios
                        $vc->alquilaSocioProducto(1, 2)->alquilaSocioProducto(1, 3);
                        // Intentos de alquiler que deben fallar (ya alquilado o cupo superado)
                        $vc->alquilaSocioProducto(1, 2);
                        $vc->alquilaSocioProducto(1, 6);
                        ?>
                        <!-- Sección de socios -->
                        <h2 class="mt-4 mb-4"><i class="bi bi-person-check"></i> Socios</h2>
                        <!-- Grid con bootstrap para mostrar varias tarjetas -->
                        <div class="row row-cols-1 row-cols-md-4 g-4">
                            <?php
                            // Listamos los socios (se renderizan con HTML y Bootstrap)
                            $vc->listarSocios();
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