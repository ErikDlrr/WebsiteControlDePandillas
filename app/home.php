<?php
// Iniciar la sesión
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.html');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: home1.php');
    exit();
}

// Obtener datos de sesión
$tipo_usuario = $_SESSION['tipo_usuario'];
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Criminal Nexus - Panel principal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="app-shell">
        <!-- HEADER -->
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Criminal Nexus</h1>
                <p class="app-subtitle">
                    Bienvenido, <?php echo htmlspecialchars(ucfirst($usuario)); ?>.
                </p>
            </div>

            <div class="app-header-right">
                <span class="badge">
                    <i class="fa-solid fa-user-shield"></i>
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

        <!-- CONTENIDO PRINCIPAL -->
        <main class="app-main">
            <section class="module-grid">

                <a href="administrador/menu2.html" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <h2 class="module-title">Gestión</h2>
                    <p class="module-description">
                        Alta, edición y consulta de pandillas, integrantes y usuarios.
                    </p>
                </a>

                <a href="modulo_incidentes.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2 class="module-title">Incidentes</h2>
                    <p class="module-description">
                        Registro y seguimiento de incidentes asociados a pandillas.
                    </p>
                </a>

                <a href="administrador/reportes_admin.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h2 class="module-title">Reportes</h2>
                    <p class="module-description">
                        Generación de reportes de pandillas por zona.
                    </p>
                </a>

                <a href="administrador/mapa_admin.php" class="module-card">
                    <div class="module-icon">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h2 class="module-title">Mapa ciudadano</h2>
                    <p class="module-description">
                        Visualiza el mapa público de zonas de riesgo.
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
