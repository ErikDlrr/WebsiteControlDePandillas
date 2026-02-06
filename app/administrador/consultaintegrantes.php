<?php
// consultaintegrantes.php
// Consulta de integrantes (con filtro opcional por nombre)

require __DIR__ . '/../php/conexion.php';

// Nombre para filtro opcional
$nombreFiltro = trim($_POST['nombre'] ?? '');

// Armamos la consulta base
$sql = "
    SELECT 
        i.id_Integrante,
        i.id_Pandilla,
        p.nombre AS nombre_pandilla,
        i.nombre,
        i.alias,
        i.fecha_de_nacimiento,
        i.`dirección` AS direccion,
        i.perfil_red_social,
        p.peligrosidad,
        i.foto
    FROM integrante i
    LEFT JOIN pandillas p ON i.id_Pandilla = p.id_Pandilla
";

$params = [];
$types  = '';

if ($nombreFiltro !== '') {
    $sql    .= " WHERE i.nombre LIKE ?";
    $params[] = '%' . $nombreFiltro . '%';
    $types  .= 's';
}

$sql .= " ORDER BY i.id_Integrante ASC";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . mysqli_error($conexion));
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Preparamos los datos en un arreglo
$integrantes = [];
if ($resultado) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $integrantes[] = $fila;
    }
    mysqli_free_result($resultado);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Integrantes - Criminal Nexus</title>

    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
            gap: 6px;
        }

        .data-table-wrapper {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid rgba(55, 65, 81, 0.9);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
        }

        thead {
            background: rgba(15, 23, 42, 0.98);
        }

        thead th {
            text-align: left;
            padding: 9px 10px;
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
            padding: 8px 10px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            color: #d1d5db;
            vertical-align: top;
        }

        tbody tr:hover {
            background: rgba(30, 64, 175, 0.35);
        }

        .foto-mini {
            max-width: 60px;
            max-height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .filter-form {
            margin-bottom: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-form input[type="text"] {
            min-width: 200px;
        }
    </style>
</head>
<body>
<div class="app-shell">
    <header class="app-header">
        <div class="app-header-left">
            <h1 class="app-title">Panel de administración</h1>
            <p class="app-subtitle">Consulta de integrantes registrados.</p>
        </div>
        <div class="app-header-right">
            <span class="badge">
                <i class="fa-solid fa-user-group"></i>&nbsp;Integrantes
            </span>
            <a href="SubmenuIntegrantes.html" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <main class="app-main">
        <section class="data-card">
            <div class="data-header">
                <div>
                    <h2>Integrantes registrados</h2>
                    <p>
                        <?php if ($nombreFiltro !== ''): ?>
                            Resultados para: <strong><?php echo htmlspecialchars($nombreFiltro); ?></strong>
                        <?php else: ?>
                            Listado general de integrantes en el sistema.
                        <?php endif; ?>
                    </p>
                </div>
                <span class="data-pill">
                    <i class="fa-solid fa-database"></i>
                    <?php echo count($integrantes); ?> registro(s)
                </span>
            </div>

            <!-- Filtro por nombre (mismo endpoint) -->
            <form method="POST" class="filter-form">
                <label for="nombre">Filtrar por nombre:</label>
                <div class="field-inner" style="max-width:260px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="nombre" id="nombre"
                           value="<?php echo htmlspecialchars($nombreFiltro); ?>">
                </div>
                <button type="submit" class="btn-primary" style="width:auto;">
                    Buscar
                </button>
                <a href="consultaintegrantes.php" class="btn-ghost" style="width:auto;">
                    Limpiar
                </a>
            </form>

            <div class="data-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pandilla</th>
                            <th>Nombre</th>
                            <th>Alias</th>
                            <th>Fecha nac.</th>
                            <th>Dirección</th>
                            <th>Perfil</th>
                            <th>Peligrosidad</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($integrantes)): ?>
                        <tr>
                            <td colspan="9">No se encontraron integrantes con los criterios especificados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($integrantes as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_Integrante']); ?></td>
                                <td>
                                    <?php
                                        if (!empty($row['nombre_pandilla'])) {
                                            echo htmlspecialchars($row['nombre_pandilla']) .
                                                 " (ID " . htmlspecialchars($row['id_Pandilla']) . ")";
                                        } else {
                                            echo "Sin asignar";
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nombre'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['alias'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_de_nacimiento'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['perfil_red_social'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['peligrosidad'] ?? ''); ?></td>
                                <td>
                                    <?php if (!empty($row['foto'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['foto']); ?>"
                                             alt="Foto"
                                             class="foto-mini">
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
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
        <p>&copy; 2025 Criminal Nexus. |
            <a target="_blank" href="http://upslp.edu.mx/">Universidad Politécnica de San Luis Potosí</a>
        </p>
    </footer>
</div>
</body>
</html>
