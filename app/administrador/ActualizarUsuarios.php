<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar usuario - Criminal Nexus</title>

    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .module-card {
            max-width: 600px;
            margin: 20px auto;
        }

        .module-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 18px;
        }

        .module-grid .field {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .module-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <header class="app-header">
        <div class="app-header-left">
            <h1 class="app-title">Panel de administración</h1>
            <p class="app-subtitle">Editar datos de usuarios.</p>
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
                <i class="fa-solid fa-pen-to-square"></i>
                Actualizar usuario
            </h2>
            <p class="module-description">
                Ingresa el ID del usuario y modifica solo los campos que necesites.
            </p>

            <form class="login" action="actualizarusuario.php" method="post">
                <div class="field">
                    <label for="id_Usuario">ID de usuario (obligatorio)</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-hashtag"></i>
                        <input type="number" name="id_Usuario" id="id_Usuario" required>
                    </div>
                </div>

                <div class="field">
                    <label for="nombre_usuario">Nombre de usuario (login)</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nombre_usuario" id="nombre_usuario">
                    </div>
                </div>

                <div class="module-grid">
                    <div class="field">
                        <label for="tipo_usuario">Rol</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-shield-halved"></i>
                            <select name="tipo_usuario" id="tipo_usuario">
                                <option value="">Sin cambio</option>
                                <option value="administrador">Administrador</option>
                                <option value="consultor">Consultor</option>
                                <option value="ciudadano">Ciudadano</option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-id-badge"></i>
                            <input type="text" name="nombre" id="nombre">
                        </div>
                    </div>

                    <div class="field">
                        <label for="apellido">Apellido</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-user-pen"></i>
                            <input type="text" name="apellido" id="apellido">
                        </div>
                    </div>

                    <div class="field">
                        <label for="puesto">Puesto</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-briefcase"></i>
                            <input type="text" name="puesto" id="puesto">
                        </div>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" id="email">
                        </div>
                    </div>

                    <div class="field">
                        <label for="contraseña">Nueva contraseña (opcional)</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-key"></i>
                            <input type="password" name="contraseña" id="contraseña">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top:14px;">
                    <span class="state">Actualizar</span>
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
