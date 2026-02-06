<?php
// CapturaIntegrantes.php
// Formulario para registrar un integrante, con listado de pandillas

require __DIR__ . '/../php/conexion.php'; 

$pandillas = [];

$sql = "SELECT id_Pandilla, nombre FROM pandillas ORDER BY nombre";
if ($resultado = mysqli_query($conexion, $sql)) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pandillas[] = $fila;
    }
    mysqli_free_result($resultado);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Captura de Integrantes - Criminal Nexus</title>

    <!-- Estilos globales (ajusta la ruta si es necesario) -->
    <link rel="stylesheet" href="../login.css">

    <!-- Iconos -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
            <h1 class="app-title">Panel de administración</h1>
            <p class="app-subtitle">Captura de nuevos integrantes.</p>
        </div>
        <div class="app-header-right">
            <span class="badge">
                <i class="fa-solid fa-user-plus"></i>&nbsp;Integrantes
            </span>
            <a href="SubmenuIntegrantes.html" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <main class="app-main">
        <section class="module-card">
            <h2 class="module-title">
                <i class="fa-solid fa-user-plus"></i>
                Registrar integrante
            </h2>
            <p class="module-description">
                Completa la información para registrar un nuevo integrante y asociarlo a una pandilla.
            </p>

            <!-- Corregido action a registrointegrantes.php -->
            <form class="login" action="registrointegrantes.php" method="post">

                <div class="field">
                    <label for="nombre">Nombre del integrante</label>
                    <div class="field-inner">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                </div>

                <div class="module-grid">
                    <div class="field">
                        <label for="apellido_paterno">Apellido Paterno</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="apellido_paterno" id="apellido_paterno" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="apellido_materno">Apellido Materno</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="apellido_materno" id="apellido_materno" required>
                        </div>
                    </div>
                </div>

                <div class="module-grid">
                    <div class="field">
                        <label for="alias">Alias</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-user-secret"></i>
                            <input type="text" name="alias" id="alias">
                        </div>
                    </div>

                    <div class="field">
                        <label for="fecha">Fecha de nacimiento</label>
                        <div class="field-inner">
                            <i class="fa-regular fa-calendar-days"></i>
                            <input type="date" name="fecha" id="fecha">
                        </div>
                    </div>

                    <div class="field">
                        <label for="direccion">Dirección (texto)</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-location-dot"></i>
                            <input type="text" name="direccion" id="direccion">
                        </div>
                    </div>

                    <div class="field">
                        <label for="id_Pandilla">Pandilla asociada</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-users"></i>
                            <select name="id_Pandilla" id="id_Pandilla" required>
                                <option value="">Selecciona una pandilla</option>
                                <?php foreach ($pandillas as $p): ?>
                                    <option value="<?php echo htmlspecialchars($p['id_Pandilla']); ?>">
                                        <?php echo htmlspecialchars($p['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="lugar">Dónde vive (dirección detallada)</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-house-chimney"></i>
                            <input type="text" name="lugar" id="lugar" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="peligrosidad">Peligrosidad (1 a 10)</label>
                        <div class="field-inner">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <input type="number" name="peligrosidad" id="peligrosidad"
                                   min="1" max="10" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top:14px;">
                    <span class="state">Guardar</span>
                </button>

                <a href="SubmenuIntegrantes.html" class="btn-ghost" style="margin-top:10px;">
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
