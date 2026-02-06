<?php
require __DIR__ . '/../../php/conexion.php';

$zona = $_POST['zona'] ?? '';
$zona = trim($zona);

$pandillas = [];
$reporteGenerado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $zona !== '') {
    $sql = "
        SELECT 
            p.id_Pandilla,
            p.nombre,
            p.descripcion,
            p.lider,
            p.numero_aproximado_de_integrantes,
            u.calle,
            u.numero_de_calle,
            u.entre_calles,
            u.colonia,
            u.localidad,
            u.zona
        FROM pandillas p
        JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
        WHERE u.zona = ?
        ORDER BY u.colonia, p.nombre
    ";

    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $zona);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $pandillas[] = $row;
            }
            $reporteGenerado = true;
        } else {
            error_log('Error execute reportes.php: ' . mysqli_error($conexion));
        }
    } else {
        error_log('Error prepare reportes.php: ' . mysqli_error($conexion));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes por zona - Consultor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../login.css">

    <style>
        .report-card {
            margin-top: 12px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.97);
            border: 1px solid rgba(55, 65, 81, 0.95);
            padding: 20px 18px 22px;
            box-shadow:
                0 22px 70px rgba(15, 23, 42, 0.96),
                0 0 0 1px rgba(15, 23, 42, 0.95);
        }

        .report-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .report-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #f9fafb;
        }

        .report-header p {
            font-size: 0.88rem;
            color: #9ca3af;
        }

        .report-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: 1px solid rgba(55, 65, 81, 0.9);
            background: rgba(15, 23, 42, 0.96);
            color: #e5e7eb;
        }

        .report-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 14px;
        }

        .report-form label {
            font-size: 0.88rem;
            color: #d1d5db;
            font-weight: 500;
        }

        .report-form select {
            min-width: 180px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid rgba(75, 85, 99, 0.9);
            background: rgba(15, 23, 42, 0.96);
            color: #e5e7eb;
            font-size: 0.88rem;
            outline: none;
        }

        .report-form select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.75);
        }

        .report-table-wrapper {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid rgba(55, 65, 81, 0.9);
            margin-top: 10px;
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
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background: rgba(15, 23, 42, 0.96);
        }

        tbody tr:nth-child(odd) {
            background: rgba(15, 23, 42, 0.93);
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            color: #d1d5db;
            vertical-align: top;
        }

        tbody tr:hover {
            background: rgba(30, 64, 175, 0.35);
        }

        .report-actions {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-secondary {
            border-radius: 999px;
            border: 1px solid rgba(55, 65, 81, 0.9);
            background: rgba(15, 23, 42, 0.96);
            padding: 8px 14px;
            font-size: 0.85rem;
            color: #e5e7eb;
            cursor: pointer;
            transition:
                border-color 0.18s ease,
                background-color 0.18s ease,
                color 0.18s ease,
                transform 0.12s ease;
        }

        .btn-secondary:hover {
            border-color: rgba(34, 197, 94, 0.8);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Panel de consultor</h1>
                <p class="app-subtitle">
                    Generación de reportes de pandillas por zona.
                </p>
            </div>
            <div class="app-header-right">
                <span class="badge">
                    Consultor
                </span>
                <form action="../../home2.php" method="post">
                    <button type="submit" class="btn-ghost">
                        Volver
                    </button>
                </form>
            </div>
        </header>

        <main class="app-main">
            <section class="report-card">
                <div class="report-header">
                    <div>
                        <h2>Reportes por zona</h2>
                        <p>Selecciona una zona para ver el listado de pandillas y generar un PDF.</p>
                    </div>
                    <span class="report-pill">
                        Reportes
                    </span>
                </div>

                <form method="POST" class="report-form">
                    <label for="zona">Zona:</label>
                    <select name="zona" id="zona" required>
                        <option value="">Selecciona una zona</option>
                        <option value="Centro" <?php if ($zona === 'Centro') echo 'selected'; ?>>Centro</option>
                        <option value="Este" <?php if ($zona === 'Este') echo 'selected'; ?>>Este</option>
                        <option value="Oeste" <?php if ($zona === 'Oeste') echo 'selected'; ?>>Oeste</option>
                        <option value="Norte" <?php if ($zona === 'Norte') echo 'selected'; ?>>Norte</option>
                        <option value="Sur" <?php if ($zona === 'Sur') echo 'selected'; ?>>Sur</option>
                    </select>

                    <button type="submit" class="btn-primary" style="width:auto;">
                        Generar reporte
                    </button>
                </form>

                <?php if ($reporteGenerado): ?>
                    <div class="report-table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Líder</th>
                                    <th>Integrantes aprox.</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pandillas)): ?>
                                    <tr><td colspan="6">No se encontraron resultados para la zona seleccionada.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($pandillas as $pandilla): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($pandilla['id_Pandilla']); ?></td>
                                            <td><?php echo htmlspecialchars($pandilla['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($pandilla['descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($pandilla['lider']); ?></td>
                                            <td><?php echo htmlspecialchars($pandilla['numero_aproximado_de_integrantes']); ?></td>
                                            <td>
                                                <?php
                                                    $dir = $pandilla['calle'] . ' ' . $pandilla['numero_de_calle'];
                                                    if (!empty($pandilla['entre_calles'])) {
                                                        $dir .= ', entre ' . $pandilla['entre_calles'];
                                                    }
                                                    $dir .= ', ' . $pandilla['colonia'] . ', ' . $pandilla['localidad'] . ', ' . $pandilla['zona'];
                                                    echo htmlspecialchars($dir);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="report-actions">
                    <?php if ($reporteGenerado && !empty($zona)): ?>
                        <button type="button" class="btn-secondary"
                                onclick="window.location.href='generador_pdf/generar_pdf.php?zona=<?php echo urlencode($zona); ?>';">
                            Descargar PDF para <?php echo htmlspecialchars($zona); ?>
                        </button>
                    <?php endif; ?>

                </div>
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
