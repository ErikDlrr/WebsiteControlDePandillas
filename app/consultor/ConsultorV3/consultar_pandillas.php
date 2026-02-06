<?php
session_start();

// Solo consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}

require __DIR__ . '/../../php/conexion.php';

$pandillas = [];

$sql = "
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
    JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
    ORDER BY u.zona, u.colonia, p.nombre
";

$result = mysqli_query($conexion, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pandillas[] = $row;
    }
} else {
    error_log('Error al consultar pandillas: ' . mysqli_error($conexion));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pandillas registradas - Consultor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../login.css">

    <style>
        .data-card {
            margin-top: 12px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.97);
            border: 1px solid rgba(55, 65, 81, 0.95);
            padding: 20px 18px 22px;
            box-shadow:
                0 22px 70px rgba(15, 23, 42, 0.96),
                0 0 0 1px rgba(15, 23, 42, 0.95);
        }

        .data-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .data-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #f9fafb;
        }

        .data-header p {
            font-size: 0.88rem;
            color: #9ca3af;
        }

        .data-pill {
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

        .data-table-wrapper {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid rgba(55, 65, 81, 0.9);
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

        .data-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Panel de consultor</h1>
                <p class="app-subtitle">
                    Listado de pandillas registradas en el sistema.
                </p>
            </div>
            <div class="app-header-right">
                <span class="badge">
                    Consultor
                </span>
                <form action="../../php/salida.php" method="post">
                    <button type="submit" class="btn-ghost">
                        Salir
                    </button>
                </form>
            </div>
        </header>

        <main class="app-main">
            <section class="data-card">
                <div class="data-header">
                    <div>
                        <h2>Pandillas registradas</h2>
                        <p>
                            Consulta rápida de nombre, líder, tamaño aproximado y localización
                            (colonia y zona) de cada pandilla.
                        </p>
                    </div>
                    <span class="data-pill">
                        Listado
                    </span>
                </div>

                <div class="data-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Líder</th>
                                <th>Integrantes aprox.</th>
                                <th>Peligrosidad</th>
                                <th>Localización</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pandillas)): ?>
                                <tr>
                                    <td colspan="7">No se encontraron pandillas registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pandillas as $pandilla): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pandilla['id_Pandilla']); ?></td>
                                        <td><?php echo htmlspecialchars($pandilla['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($pandilla['descripcion']); ?></td>
                                        <td><?php echo htmlspecialchars($pandilla['lider']); ?></td>
                                        <td><?php echo htmlspecialchars($pandilla['numero_aproximado_de_integrantes']); ?></td>
                                        <td><?php echo htmlspecialchars($pandilla['peligrosidad']); ?></td>
                                        <td>
                                            <?php
                                                echo htmlspecialchars($pandilla['colonia']);
                                                if (!empty($pandilla['zona'])) {
                                                    echo ' — ' . htmlspecialchars($pandilla['zona']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="data-actions">
                    <a href="consultor.php" class="link-pill">
                        <span class="link-pill-label">Ver mapa</span>
                    </a>
                    <a href="cambiar_contra.html" class="link-pill">
                        <span class="link-pill-label">Cambiar contraseña</span>
                    </a>
                    <a href="form_error.html" class="link-pill">
                        <span class="link-pill-label">Reportar error</span>
                    </a>
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
