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
                            <span class="visually-hidden">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-bookmark"></i> Sobre el proyecto</a>
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
            // Requerimos el autoload para cargar las clases correctamente
            require_once("../autoload.php");

            use Dwes\ProyectoVideoclub\Cliente;
            use Dwes\ProyectoVideoclub\CintaVideo;
            use Dwes\ProyectoVideoclub\Dvd;
            use Dwes\ProyectoVideoclub\Juego;

            // Instanciamos un par de objetos cliente
            $cliente1 = new Cliente("Bruce Wayne", 23);
            $cliente2 = new Cliente("Clark Kent", 33);
            ?>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="mt-4 mb-4"><i class="bi bi-person"></i> Identificadores de clientes</h2>
                        <div class="alert alert-info">
                            <?php
                            echo "El identificador del cliente 1 es: <strong>" . $cliente1->getNumero() . "</strong>";
                            echo "<br>El identificador del cliente 2 es: <strong>" . $cliente2->getNumero() . "</strong>";
                            ?>
                        </div>
                        <hr>
                        <h2 class="mt-4 mb-4"><i class="bi bi-bag"></i> Soportes y operaciones</h2>
                        <?php
                        // Instancio algunos soportes
                        $soporte1 = new CintaVideo("Los cazafantasmas", 23, 3.5, 107);
                        $soporte2 = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1, 1);
                        $soporte3 = new Dvd("Origen", 24, 15, "es,en,fr", "16:9");
                        $soporte4 = new Dvd("El Imperio Contraataca", 4, 3, "es,en", "16:9");

                        // Alquilo algunos soportes
                        echo '<div class="alert alert-success mt-2">Alquilando soportes para Bruce Wayne...</div>';
                        try {
                            $cliente1->alquilar($soporte1);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        try {
                            $cliente1->alquilar($soporte2);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        try {
                            $cliente1->alquilar($soporte3);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }

                        // Intento alquilar de nuevo un soporte que ya tiene alquilado
                        echo '<div class="alert alert-warning mt-2">Intentando alquilar de nuevo "Los cazafantasmas"...</div>';
                        try {
                            $cliente1->alquilar($soporte1);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // El cliente tiene 3 soportes en alquiler como máximo
                        // Este soporte no lo va a poder alquilar
                        echo '<div class="alert alert-warning mt-2">Intentando alquilar "El Imperio Contraataca" (exceso de cupo)...</div>';
                        try {
                            $cliente1->alquilar($soporte4);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // Este soporte no lo tiene alquilado
                        echo '<div class="alert alert-info mt-2">Intentando devolver "El Imperio Contraataca" (no alquilado)...</div>';
                        try {
                            $cliente1->devolver(4);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // Devuelvo un soporte que sí que tiene alquilado
                        echo '<div class="alert alert-success mt-2">Devolviendo "The Last of Us Part II"...</div>';
                        try {
                            $cliente1->devolver(26);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // Alquilo otro soporte
                        echo '<div class="alert alert-success mt-2">Alquilando "El Imperio Contraataca"...</div>';
                        try {
                            $cliente1->alquilar($soporte4);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // Listo los elementos alquilados
                        echo '<div class="alert alert-info mt-2">Alquileres actuales de Bruce Wayne:</div>';
                        try {
                            
                            $cliente1->listarAlquileres();
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        // Este cliente no tiene alquileres
                        echo '<div class="alert alert-info mt-2">Intentando devolución para Clark Kent (sin alquileres)...</div>';
                        try {
                            $cliente2->devolver(2);
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger mt-2">' . $e->getMessage() . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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