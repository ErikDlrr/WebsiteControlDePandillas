<?php
// consultaUsuarios.php
require __DIR__ . '/../php/conexion.php';

$usuarioFiltro = trim($_POST['nombre_usuario'] ?? '');

$sql = "SELECT id_Usuario, nombre_Usuario, tipo_Usuario, Nombre, Apellido, Puesto, Email 
        FROM usuarios";

$params = [];
$types  = '';

if ($usuarioFiltro !== '') {
    $sql     .= " WHERE nombre_Usuario LIKE ?";
    $params[] = '%' . $usuarioFiltro . '%';
    $types   .= 's';
}

$sql .= " ORDER BY id_Usuario ASC";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($conexion));
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$usuarios = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de usuarios - Criminal Nexus</title>

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
            <p class="app-subtitle">Consulta de usuarios del sistema.</p>
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
        <section class="data-card">
            <div class="data-header">
                <div>
                    <h2>Usuarios registrados</h2>
                    <p>
                        <?php if ($usuarioFiltro !== ''): ?>
                            Resultados para: <strong><?php echo htmlspecialchars($usuarioFiltro); ?></strong>
                        <?php else: ?>
                            Listado general de usuarios.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <form method="POST" class="filter-form">
                <label for="nombre_usuario">Filtrar por usuario:</label>
                <div class="field-inner" style="max-width:260px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="nombre_usuario" id="nombre_usuario"
                           value="<?php echo htmlspecialchars($usuarioFiltro); ?>">
                </div>
                <button type="submit" class="btn-primary" style="width:auto;">
                    Buscar
                </button>
                <a href="consultaUsuarios.php" class="btn-ghost" style="width:auto;">
                    Limpiar
                </a>
            </form>

            <div class="data-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Puesto</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7">No se encontraron usuarios con los criterios especificados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['id_Usuario']); ?></td>
                                <td><?php echo htmlspecialchars($u['nombre_Usuario']); ?></td>
                                <td><?php echo htmlspecialchars($u['tipo_Usuario']); ?></td>
                                <td><?php echo htmlspecialchars($u['Nombre']); ?></td>
                                <td><?php echo htmlspecialchars($u['Apellido']); ?></td>
                                <td><?php echo htmlspecialchars($u['Puesto']); ?></td>
                                <td><?php echo htmlspecialchars($u['Email']); ?></td>
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
