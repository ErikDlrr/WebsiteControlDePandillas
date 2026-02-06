<?php
// registrointegrantes.php
// Inserta un nuevo integrante en la tabla `integrante`

require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: CapturaIntegrantes.php');
    exit();
}

// Sanitizar/normalizar entradas
$nombre           = trim($_POST['nombre'] ?? '');
$apellidoPaterno  = trim($_POST['apellido_paterno'] ?? '');
$apellidoMaterno  = trim($_POST['apellido_materno'] ?? '');
$alias            = trim($_POST['alias'] ?? '');
$fecha            = trim($_POST['fecha'] ?? '');       // fecha_de_nacimiento
$direccion        = trim($_POST['direccion'] ?? '');   // dirección textual
$lugar            = trim($_POST['lugar'] ?? '');       // opcional: se puede anexar a dirección
$id_Pandilla      = isset($_POST['id_Pandilla']) ? (int)$_POST['id_Pandilla'] : 0;
$peligrosidad     = isset($_POST['peligrosidad']) ? (int)$_POST['peligrosidad'] : 0;

// Validaciones mínimas
if ($nombre === '' || $apellidoPaterno === '' || $apellidoMaterno === '') {
    echo "Faltan datos obligatorios (nombre o apellidos). ";
    echo "<a href='CapturaIntegrantes.php'>Volver</a>";
    exit();
}

// Combinar dirección y lugar si ambos existen
$direccionCompleta = $direccion;
if ($lugar !== '') {
    $direccionCompleta = trim($direccionCompleta . ' ' . $lugar);
}

// Insert con prepared statement
$sql = "
    INSERT INTO integrante (
        nombre,
        apellido_paterno,
        apellido_materno,
        alias,
        fecha_de_nacimiento,
        `dirección`,
        id_Pandilla,
        peligrosidad
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo "Error al preparar la consulta: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

// Tipos:
// s = string
// i = integer
$tipos = 'ssssssii';

mysqli_stmt_bind_param(
    $stmt,
    $tipos,
    $nombre,
    $apellidoPaterno,
    $apellidoMaterno,
    $alias,
    $fecha,
    $direccionCompleta,
    $id_Pandilla,
    $peligrosidad
);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error al insertar: " . htmlspecialchars(mysqli_stmt_error($stmt));
} else {
    echo "Registro de integrante exitoso. <a href='CapturaIntegrantes.php'>Volver</a>";
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
