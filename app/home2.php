<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.html');
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Criminal Nexus - Panel analista</title>

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
                <h1 class="app-title">Panel de consultas rapidas</h1>
                <p class="app-subtitle">
                    Bienvenido, <?php echo htmlspecialchars(ucfirst($usuario)); ?>.
                </p>
            </div>

            <div class="app-header-right">
                <span class="badge">
                    <i class="fa-solid fa-user-gear"></i>
                    &nbsp;<?php echo htmlspecialchars(ucfirst($tipo_usuario)); ?>
                </span>

                <form action="home1.php" method="post">
                    <button type="submit" class="btn-ghost">
                        <i class="fa-solid fa-arrow-left"></i>
                        Volver
                    </button>
                </form>
            </div>
        </header>

        <main class="app-main">
            <section class="module-grid">
                <a href="consultor/ConsultorV3/reportes.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h2 class="module-title">Reportes</h2>
                    <p class="module-description">
                        Consulta y genera reportes por periodo, zona y tipo de incidente.
                    </p>
                </a>

                <a href="consultor/ConsultorV3/estadisticas.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h2 class="module-title">Estadísticas</h2>
                    <p class="module-description">
                        Visualiza tendencias y patrones de comportamiento.
                    </p>
                </a>

                <a href="consultor/ConsultorV3/consultor.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <h2 class="module-title">Mapa de pandillas</h2>
                    <p class="module-description">
                        Revisa las zonas con mayor presencia y puntos de reunión.
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
