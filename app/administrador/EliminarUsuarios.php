<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eliminar usuario - Criminal Nexus</title>

    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .module-card {
            max-width: 480px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
<div class="app-shell">
    <header class="app-header">
        <div class="app-header-left">
            <h1 class="app-title">Panel de administración</h1>
            <p class="app-subtitle">Eliminar usuarios del sistema.</p>
        </div>
        <div class="app-header-right">
            <span class="badge">
                <i class="fa-solid fa-users-gear"></i>&nbsp;Usuarios
            </span>
            <a href="SubmenuUsuarios.html" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <main class="app-main">
        <section class="module-card">
            <h2 class="module-title">
                <i class="fa-solid fa-user-xmark"></i>
                Eliminar usuario
            </h2>
            <p class="module-description">
                Ingresa el ID del usuario que deseas eliminar.
            </p>

            <form class="login" action="eliminarusuario.php" method="post">
                <div class="field">
                    <label for="id_Usuario">ID de usuario</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-hashtag"></i>
                        <input type="number" name="id_Usuario" id="id_Usuario" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <span class="state">Eliminar</span>
                </button>

                <a href="SubmenuUsuarios.html" class="btn-ghost" style="margin-top:10px;">
                    Cancelar
                </a>
            </form>
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
