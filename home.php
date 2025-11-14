<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Videoclub PHP - Inicio</title>
    <!-- Bootstrap y Bootstrap Icons -->
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="vendor/styles/custom.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
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
                        <a class="nav-link active" href="#"><i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="bi bi-filetype-php"></i> Pruebas</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="test/inicio.php">Inicio.php</a>
                            <a class="dropdown-item" href="test/inicio2.php">Inicio2.php</a>
                            <a class="dropdown-item" href="test/inicio3.php">Inicio3.php</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal con pestañas -->
    <main class="hero">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Videoclub PHP</h1>
            <p class="lead mb-4">Aplicación demostrativa de gestión de un videoclub usando PHP y Bootstrap.</p>

            <!-- Navegación por pestañas -->
            <ul class="nav nav-tabs" role="tablist">
                <div class="w-100 d-flex justify-content-center">
                    <ul class="nav nav-tabs border-0" role="tablist" style="border: none; box-shadow: none; background: transparent;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#testing" aria-selected="true" role="tab">Testing</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#about" aria-selected="false" role="tab" tabindex="-1">Sobre el proyecto</a>
                        </li>
                    </ul>
                </div>
            </ul>
            <div class="tab-content">
                <!-- Pestaña de testing con accesos directos -->
                <div class="tab-pane fade active show" id="testing" role="tabpanel">
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a class="btn btn-primary btn-lg" href="test/inicio.php"><i class="bi bi-play-circle"></i> ´`Ir a inicio.php</a>
                        <a class="btn btn-secondary btn-lg" href="test/inicio2.php"><i class="bi bi-play-btn"></i> Ir a inicio2.php</a>
                        <a class="btn btn-success btn-lg" href="test/inicio3.php"><i class="bi bi-controller"></i> Ir a inicio3.php</a>
                    </div>
                </div>
                <!-- Pestaña sobre el proyecto -->
                <div class="tab-pane fade" id="about" role="tabpanel">
                    <div class="col">
                        <h2 class="mt-4 mb-4"><i class="bi bi-info"></i> Sobre el proyecto</h2>
                        <div class="col">
                            <div class="card h-100 w-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-film"></i> Proyecto Videoclub PHP</h5>
                                    <p class="card-text">
                                        Este proyecto simula la gestión de un videoclub, permitiendo administrar socios
                                        y soportes (cintas de vídeo, DVDs y juegos), así como el alquiler y devolución
                                        de estos productos.<br>
                                        <strong><i class="bi bi-flag"></i> Finalidad:</strong> Aprender y aplicar
                                        programación orientada a objetos en PHP, buenas prácticas y diseño de interfaces
                                        con <i class="bi bi-bootstrap"></i> Bootstrap.<br>
                                        <strong><i class="bi bi-people"></i> Autores:</strong> Darío Muñoz Rodríguez y
                                        Yago García Alonso (2 DAW).<br>
                                        <strong><i class="bi bi-link-45deg"></i> Referencia de código:</strong> <a
                                            href="https://aitor-medrano.github.io/dwes2122/03phpoo.html#proyecto-videoclub"
                                            target="_blank">Proyecto Videoclub - Aitor Medrano</a>.<br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <!-- Bootstrap Bundle con Popper -->
    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>

</html>