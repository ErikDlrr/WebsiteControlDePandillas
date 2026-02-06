<?php
session_start();

// Restringir a consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}

require __DIR__ . '/../../php/conexion.php';

$usuario = $_SESSION['usuario'];
$mensaje = '';
$error = '';

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id_Pandilla = $_POST['id_Pandilla'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $lider = $_POST['lider'] ?? '';
    $integrantes = $_POST['numero_integrantes'] ?? 0;
    $peligrosidad = $_POST['peligrosidad'] ?? '';
    
    // Campos adicionales que podrían estar en la tabla pandillas o relacionados
    // Para simplificar y basándonos en actualizar_pandillas.php, actualizaremos lo básico en 'pandillas'
    
    if ($id_Pandilla) {
        // Construir query dinámica o fija según los campos disponibles
        $sqlUpdate = "UPDATE pandillas SET nombre=?, descripcion=?, lider=?, numero_aproximado_de_integrantes=?, peligrosidad=? WHERE id_Pandilla=?";
        $stmtUpdate = mysqli_prepare($conexion, $sqlUpdate);
        
        if ($stmtUpdate) {
            mysqli_stmt_bind_param($stmtUpdate, 'sssisi', $nombre, $descripcion, $lider, $integrantes, $peligrosidad, $id_Pandilla);
            if (mysqli_stmt_execute($stmtUpdate)) {
                $mensaje = "Pandilla actualizada correctamente.";
            } else {
                $error = "Error al actualizar pandilla: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmtUpdate);
        } else {
            $error = "Error al preparar consulta: " . mysqli_error($conexion);
        }
    } else {
        $error = "ID de pandilla no válido.";
    }
}

// Obtener pandillas para la tabla
$pandillas = [];
$sqlPandillas = "
    SELECT 
        p.id_Pandilla,
        p.nombre,
        p.descripcion,
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.peligrosidad,
        u.colonia,
        u.zona
    FROM pandillas p
    LEFT JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
    ORDER BY p.nombre
";
$resultPandillas = mysqli_query($conexion, $sqlPandillas);
if ($resultPandillas) {
    while ($row = mysqli_fetch_assoc($resultPandillas)) {
        $pandillas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pandillas - Criminal Nexus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .module-card {
            margin-top: 12px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.97);
            border: 1px solid rgba(55, 65, 81, 0.95);
            padding: 20px 18px 22px;
            box-shadow: 0 22px 70px rgba(15, 23, 42, 0.96), 0 0 0 1px rgba(15, 23, 42, 0.95);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-group label {
            font-size: 0.88rem;
            color: #d1d5db;
            font-weight: 500;
        }
        .form-group input, .form-group select, .form-group textarea {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(75, 85, 99, 0.9);
            background: rgba(15, 23, 42, 0.96);
            color: #e5e7eb;
            font-size: 0.9rem;
            outline: none;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.75);
        }
        .table-wrapper {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid rgba(55, 65, 81, 0.9);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
        }
        thead {
            background: rgba(15, 23, 42, 0.98);
        }
        thead th {
            text-align: left;
            padding: 10px 12px;
            font-weight: 500;
            color: #e5e7eb;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
        }
        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            color: #d1d5db;
        }
        .btn-edit {
            color: #3b82f6;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-edit:hover {
            color: #2563eb;
        }
        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 0.9rem;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Gestión de Pandillas</h1>
                <p class="app-subtitle">Consulta y actualización de información de pandillas.</p>
            </div>
            <div class="app-header-right">
                <span class="badge"><i class="fa-solid fa-user-tie"></i>&nbsp;Consultor</span>
                <a href="../../home1.php" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
            </div>
        </header>

        <main class="app-main">
            <section class="module-card">
                <?php if ($mensaje): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <h2 style="color: #f9fafb; margin-bottom: 14px;">Editar Pandilla</h2>
                <form method="POST" id="form-pandilla">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id_Pandilla" id="id_Pandilla">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="lider">Líder:</label>
                            <input type="text" name="lider" id="lider">
                        </div>
                        <div class="form-group">
                            <label for="numero_integrantes">Integrantes Aprox.:</label>
                            <input type="number" name="numero_integrantes" id="numero_integrantes">
                        </div>
                        <div class="form-group">
                            <label for="peligrosidad">Peligrosidad:</label>
                            <select name="peligrosidad" id="peligrosidad">
                                <option value="">Selecciona</option>
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn-primary" style="width: auto;" id="btn-submit" disabled>Actualizar Pandilla</button>
                        <button type="button" class="btn-ghost" onclick="limpiarFormulario()" style="width: auto;">Cancelar / Limpiar</button>
                    </div>
                </form>

                <h2 style="color: #f9fafb; margin-top: 30px; margin-bottom: 14px;">Listado de Pandillas</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Líder</th>
                                <th>Peligrosidad</th>
                                <th>Zona</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pandillas)): ?>
                                <tr><td colspan="5" style="text-align: center;">No hay pandillas registradas.</td></tr>
                            <?php else: ?>
                                <?php foreach ($pandillas as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($p['lider']); ?></td>
                                        <td><?php echo htmlspecialchars($p['peligrosidad']); ?></td>
                                        <td><?php echo htmlspecialchars($p['zona'] ?? 'N/A'); ?></td>
                                        <td>
                                            <button class="btn-edit" onclick='cargarPandilla(<?php echo json_encode($p); ?>)'>
                                                <i class="fa-solid fa-pen-to-square"></i> Editar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 Criminal Nexus. | <a target="_blank" href="http://upslp.edu.mx/">Universidad Politécnica de San Luis Potosí</a></p>
        </footer>
    </div>

    <script>
        function cargarPandilla(data) {
            document.getElementById('id_Pandilla').value = data.id_Pandilla;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('lider').value = data.lider;
            document.getElementById('numero_integrantes').value = data.numero_aproximado_de_integrantes;
            document.getElementById('peligrosidad').value = data.peligrosidad;
            document.getElementById('descripcion').value = data.descripcion;
            
            document.getElementById('btn-submit').disabled = false;
            document.getElementById('btn-submit').innerText = "Actualizar " + data.nombre;
            
            // Scroll to form
            document.querySelector('.module-card').scrollIntoView({ behavior: 'smooth' });
        }

        function limpiarFormulario() {
            document.getElementById('form-pandilla').reset();
            document.getElementById('id_Pandilla').value = '';
            document.getElementById('btn-submit').disabled = true;
            document.getElementById('btn-submit').innerText = "Actualizar Pandilla";
        }
    </script>
</body>
</html>
