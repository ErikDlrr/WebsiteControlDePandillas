<?php
session_start();

// Restringir a administrador
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'administrador')) {
    header('Location: index.html');
    exit();
}

require __DIR__ . '/php/conexion.php';

$usuario = $_SESSION['usuario'];
$mensaje = '';
$error = '';

// Procesar formulario de alta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
    $id_Pandilla = $_POST['id_Pandilla'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $evento = $_POST['evento'] ?? '';

    if ($id_Pandilla && $fecha && $hora && $evento) {
        // Obtener el siguiente ID manualmente (ya que no hay AUTO_INCREMENT)
        $sqlMax = "SELECT MAX(id_antecedente) as max_id FROM antecedentes";
        $resultMax = mysqli_query($conexion, $sqlMax);
        $rowMax = mysqli_fetch_assoc($resultMax);
        $nextId = ($rowMax['max_id'] ?? 0) + 1;

        $sqlInsert = "INSERT INTO antecedentes (id_antecedente, id_Pandilla, fecha, hora, evento) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($conexion, $sqlInsert);
        if ($stmtInsert) {
            mysqli_stmt_bind_param($stmtInsert, 'iisss', $nextId, $id_Pandilla, $fecha, $hora, $evento);
            if (mysqli_stmt_execute($stmtInsert)) {
                $mensaje = "Incidente registrado correctamente.";
            } else {
                $error = "Error al registrar incidente: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmtInsert);
        } else {
            $error = "Error al preparar consulta: " . mysqli_error($conexion);
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    $sqlDelete = "DELETE FROM antecedentes WHERE id_antecedente = ?";
    $stmtDelete = mysqli_prepare($conexion, $sqlDelete);
    if ($stmtDelete) {
        mysqli_stmt_bind_param($stmtDelete, 'i', $idEliminar);
        if (mysqli_stmt_execute($stmtDelete)) {
            $mensaje = "Incidente eliminado correctamente.";
        } else {
            $error = "Error al eliminar incidente: " . mysqli_error($conexion);
        }
        mysqli_stmt_close($stmtDelete);
    }
}

// Obtener pandillas para el select
$pandillas = [];
$sqlPandillas = "SELECT id_Pandilla, nombre FROM pandillas ORDER BY nombre";
$resultPandillas = mysqli_query($conexion, $sqlPandillas);
if ($resultPandillas) {
    while ($row = mysqli_fetch_assoc($resultPandillas)) {
        $pandillas[] = $row;
    }
}

// Obtener incidentes para la tabla
$incidentes = [];
$sqlIncidentes = "
    SELECT a.id_antecedente, a.fecha, a.hora, a.evento, p.nombre as nombre_pandilla 
    FROM antecedentes a 
    JOIN pandillas p ON a.id_Pandilla = p.id_Pandilla 
    ORDER BY a.fecha DESC, a.hora DESC
";
$resultIncidentes = mysqli_query($conexion, $sqlIncidentes);
if ($resultIncidentes) {
    while ($row = mysqli_fetch_assoc($resultIncidentes)) {
        $incidentes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes - Criminal Nexus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
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
        .btn-danger {
            color: #ef4444;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-danger:hover {
            color: #dc2626;
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
                <h1 class="app-title">Gestión de Incidentes</h1>
                <p class="app-subtitle">Registro y consulta de antecedentes de pandillas.</p>
            </div>
            <div class="app-header-right">
                <span class="badge"><i class="fa-solid fa-shield-halved"></i>&nbsp;Administrador</span>
                <a href="home.php" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Volver</a>
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

                <h2 style="color: #f9fafb; margin-bottom: 14px;">Registrar Nuevo Incidente</h2>
                <form method="POST">
                    <input type="hidden" name="accion" value="registrar">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_Pandilla">Pandilla:</label>
                            <select name="id_Pandilla" id="id_Pandilla" required>
                                <option value="">Selecciona una pandilla</option>
                                <?php foreach ($pandillas as $p): ?>
                                    <option value="<?php echo $p['id_Pandilla']; ?>">
                                        <?php echo htmlspecialchars($p['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora:</label>
                            <input type="time" name="hora" id="hora" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="evento">Descripción del Evento:</label>
                        <textarea name="evento" id="evento" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width: auto;">Registrar Incidente</button>
                </form>

                <h2 style="color: #f9fafb; margin-top: 30px; margin-bottom: 14px;">Historial de Incidentes</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Pandilla</th>
                                <th>Evento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($incidentes)): ?>
                                <tr><td colspan="5" style="text-align: center;">No hay incidentes registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($incidentes as $inc): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($inc['fecha']); ?></td>
                                        <td><?php echo htmlspecialchars($inc['hora']); ?></td>
                                        <td><?php echo htmlspecialchars($inc['nombre_pandilla']); ?></td>
                                        <td><?php echo htmlspecialchars($inc['evento']); ?></td>
                                        <td>
                                            <a href="?eliminar=<?php echo $inc['id_antecedente']; ?>" class="btn-danger" onclick="return confirm('¿Estás seguro de eliminar este incidente?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
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
</body>
</html>
