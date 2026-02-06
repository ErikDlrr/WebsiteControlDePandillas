<?php
// consultapandillas.php
// Consulta de pandillas (con filtro opcional por nombre o tabla completa)

require __DIR__ . '/../php/conexion.php';

// Leer filtros desde el formulario
$nombreFiltro = trim($_POST['nombre'] ?? '');
$todaTabla    = isset($_POST['toda_tabla']) && $_POST['toda_tabla'] === 'true';

// Construcción de consulta
$sql = "
    SELECT 
        p.id_Pandilla,
        p.nombre,
        p.descripcion,
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.edades_aproximadas,
        p.perfil_Red_Social,
        p.Horario_de_reunion,
        p.peligrosidad,
        p.direccion,
        p.fecha_de_aniversario,
        u.calle,
        u.numero_de_calle,
        u.entre_calles,
        u.colonia,
        u.localidad,
        u.zona
    FROM pandillas p
    LEFT JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
";

$params = [];
$types  = '';

if (!$todaTabla && $nombreFiltro !== '') {
    $sql    .= " WHERE p.nombre LIKE ?";
    $params[] = '%' . $nombreFiltro . '%';
    $types  .= 's';
}

$sql .= " ORDER BY p.id_Pandilla ASC";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($conexion));
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$pandillas = [];
if ($resultado) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pandillas[] = $fila;
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
    <title>Consulta de Pandillas - Criminal Nexus</title>

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
            <p class="app-subtitle">Consulta de pandillas registradas.</p>
        </div>
        <div class="app-header-right">
            <span class="badge">
                <i class="fa-solid fa-users"></i>&nbsp;Pandillas
            </span>
            <a href="SubmenuPandillas.html" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </header>

    <main class="app-main">
        <section class="data-card">
            <div class="data-header">
                <div>
                    <h2>Pandillas registradas</h2>
                    <p>
                        <?php if ($todaTabla): ?>
                            Mostrando toda la tabla de pandillas.
                        <?php elseif ($nombreFiltro !== ''): ?>
                            Resultados para: <strong><?php echo htmlspecialchars($nombreFiltro); ?></strong>
                        <?php else: ?>
                            Puedes filtrar por nombre o ver toda la tabla desde el formulario.
                        <?php endif; ?>
                    </p>
                </div>
                <span class="data-pill">
                    <i class="fa-solid fa-database"></i>
                    <?php echo count($pandillas); ?> registro(s)
                </span>
            </div>

            <!-- Filtro / acciones -->
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
            </form>

            <form method="POST" style="margin-bottom: 12px;">
                <input type="hidden" name="toda_tabla" value="true">
                <button type="submit" class="btn-ghost" style="width:auto;">
                    Ver toda la tabla
                </button>
            </form>

            <div class="data-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Líder</th>
                            <th>Integrantes</th>
                            <th>Peligrosidad</th>
                            <th>Dirección</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($pandillas)): ?>
                        <tr>
                            <td colspan="8">No se encontraron pandillas con los criterios especificados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pandillas as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_Pandilla']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($row['lider']); ?></td>
                                <td><?php echo htmlspecialchars($row['numero_aproximado_de_integrantes']); ?></td>
                                <td><?php echo htmlspecialchars($row['peligrosidad']); ?></td>
                                <td>
                                    <?php
                                        $dir = $row['calle'] . ' ' . $row['numero_de_calle'];
                                        if (!empty($row['entre_calles'])) {
                                            $dir .= ', entre ' . $row['entre_calles'];
                                        }
                                        $dir .= ', ' . $row['colonia'] . ', ' . $row['localidad'];
                                        echo htmlspecialchars($dir);
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['zona']); ?></td>
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
