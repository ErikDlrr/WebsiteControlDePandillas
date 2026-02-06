<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.html');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'consultor') {
    // Si no es consultor, redirigir al panel principal
    header('Location: home.php');
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Criminal Nexus - Panel Consultor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Panel de Consultor</h1>
                <p class="app-subtitle">
                    Sesión iniciada como <?php echo htmlspecialchars(ucfirst($usuario)); ?>.
                </p>
            </div>

            <div class="app-header-right">
                <span class="badge">
                    <i class="fa-solid fa-user-tag"></i>
                    &nbsp;<?php echo htmlspecialchars(ucfirst($tipo_usuario)); ?>
                </span>

                <form action="php/salida.php" method="post">
                    <button type="submit" class="btn-ghost">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Salir
                    </button>
                </form>
            </div>
        </header>

        <main class="app-main">
            <section class="module-grid">
                <a href="consultor/ConsultorV3/modulo_incidentes.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h2 class="module-title">Captura de incidentes</h2>
                    <p class="module-description">
                        Registra nuevos incidentes y actualiza su estado.
                    </p>
                </a>

                <a href="consultor/ConsultorV3/modulo_pandillas.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <h2 class="module-title">Actualización de pandillas</h2>
                    <p class="module-description">
                        Modifica datos básicos de pandillas y ubicaciones.
                    </p>
                </a>

                <a href="home2.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h2 class="module-title">Consultas rápidas</h2>
                    <p class="module-description">
                        Consulta registros por nombre, zona o tipo de incidente.
                    </p>
                </a>
            </section>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 Criminal Nexus. |
                <a target="_blank" href="http://upslp.edu.mx/">Universidad Politécnica de San Luis Potosí</a>
            </p>
        </footer>
    </div>
</body>
</html>
