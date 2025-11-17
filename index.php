<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Videoclub PHP - Login</title>
    <link rel="stylesheet" href="vendor/styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="vendor/styles/custom.css">
    <style>
        .login-card { max-width: 400px; margin: 3rem auto; }
        .error-alert { max-width: 400px; margin: 1rem auto; }
    </style>
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="card login-card shadow">
            <div class="card-body text-center">
                <h1 class="h3 mb-4"><i class="bi bi-film text-primary"></i> Videoclub</h1>

                <!-- Mensaje de error -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger error-alert">
                        Usuario o contraseña incorrectos.
                    </div>
                <?php endif; ?>

                <!-- Formulario de login -->
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </button>
                </form>

                <hr>
                <small class="text-muted">
                    Usa: <code>admin/admin</code> o <code>usuario/usuario</code>
                </small>
            </div>
        </div>
    </div>

    <script src="vendor/scripts/bootstrap.min.js"></script>
</body>
</html>