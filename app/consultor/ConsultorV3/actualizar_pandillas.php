<?php
session_start();

// Restringir a consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Pandillas - Criminal Nexus</title>
    <link rel="stylesheet" href="../../login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .module-card {
            max-width: 720px;
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
            <h1 class="app-title">Panel de consultor</h1>
            <p class="app-subtitle">Actualizar datos de pandillas.</p>
        </div>
        <div class="app-header-right">
            <span class="badge">
                <i class="fa-solid fa-user-tie"></i>&nbsp;Consultor
            </span>
            <a href="consultor.php" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <main class="app-main">
        <section class="module-card">
            <h2 class="module-title">
                <i class="fa-solid fa-pen-to-square"></i>
                Actualizar pandilla
            </h2>
            <p class="module-description">
                Ingresa el ID de la pandilla que deseas modificar y actualiza únicamente los campos necesarios.
            </p>

            <form class="login" id="registro" action="actualizar_pandillas_handler.php" method="post">
                <div class="field">
                    <label for="id_Pandilla">ID de la pandilla (requerido)</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-hashtag"></i>
                        <input type="number" name="id_Pandilla" id="id_Pandilla" required>
                    </div>
                </div>

                <div class="field">
                    <label for="nombre">Nombre de la pandilla</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-users"></i>
                        <input type="text" name="nombre" id="nombre">
                    </div>
                </div>

                <div class="field">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"></textarea>
                </div>

                <div class="module-grid">
                    <div class="field">
                        <label for="lider">Líder</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-user-tie"></i>
                            <input type="text" name="lider" id="lider">
                        </div>
                    </div>

                    <div class="field">
                        <label for="numero_integrantes">Número aprox. de integrantes</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-users-line"></i>
                            <input type="number" name="numero_integrantes" id="numero_integrantes" min="1">
                        </div>
                    </div>

                    <div class="field">
                        <label for="edades_aproximadas">Edades aproximadas</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-calendar-day"></i>
                            <input type="text" name="edades_aproximadas" id="edades_aproximadas">
                        </div>
                    </div>

                    <div class="field">
                        <label for="perfil_red_social">Perfil en red social</label>
                        <div class="field-inner">
                            <i class="fa-brands fa-instagram"></i>
                            <input type="text" name="perfil_red_social" id="perfil_red_social">
                        </div>
                    </div>

                    <div class="field">
                        <label for="edades">Edades de los miembros</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-people-group"></i>
                            <input type="text" name="edades" id="edades">
                        </div>
                    </div>

                    <div class="field">
                        <label for="horario_reunion">Horario de reunión</label>
                        <div class="field-inner">
                            <i class="fa-regular fa-clock"></i>
                            <input type="text" name="horario_reunion" id="horario_reunion">
                        </div>
                    </div>

                    <div class="field">
                        <label for="peligrosidad">Peligrosidad</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <select name="peligrosidad" id="peligrosidad">
                                <option value="">Selecciona</option>
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="direccion">Dirección (ID o referencia)</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-location-dot"></i>
                            <input type="text" name="direccion" id="direccion">
                        </div>
                    </div>

                    <div class="field">
                        <label for="fecha_aniversario">Fecha de aniversario</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-cake-candles"></i>
                            <input type="date" name="fecha_aniversario" id="fecha_aniversario">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top:14px;">
                    <span class="state">Actualizar</span>
                </button>

                <a href="consultor.php" class="btn-ghost" style="margin-top:10px;">
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
