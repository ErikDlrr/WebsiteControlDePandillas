<?php
// registrointegrantes2.php
// Actualiza datos de un integrante en la tabla `integrante`

require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: SubmenuIntegrantes.html');
    exit();
}

$id_Integrante = isset($_POST['id_Integrante']) ? (int)$_POST['id_Integrante'] : 0;

$nombre        = trim($_POST['nombre'] ?? '');
$alias         = trim($_POST['alias'] ?? '');
$fecha         = trim($_POST['fecha'] ?? '');       // fecha_de_nacimiento
$direccion     = trim($_POST['direccion'] ?? '');   // dirección textual
$lugar         = trim($_POST['lugar'] ?? '');       // opcional: se puede anexar a dirección
$id_Pandilla   = isset($_POST['id_Pandilla']) ? (int)$_POST['id_Pandilla'] : 0;
$peligrosidad  = isset($_POST['peligrosidad']) ? (int)$_POST['peligrosidad'] : 0;

if ($id_Integrante <= 0) {
    echo "Error: El ID del integrante es requerido.";
    exit();
}

// Construcción dinámica del UPDATE solo con campos que vengan llenos
$campos  = [];
$valores = [];
$tipos   = '';

// Nombre
if ($nombre !== '') {
    $campos[]  = "nombre = ?";
    $valores[] = $nombre;
    $tipos    .= 's';
}

$apellidoPaterno = trim($_POST['apellido_paterno'] ?? '');
if ($apellidoPaterno !== '') {
    $campos[]  = "apellido_paterno = ?";
    $valores[] = $apellidoPaterno;
    $tipos    .= 's';
}

$apellidoMaterno = trim($_POST['apellido_materno'] ?? '');
if ($apellidoMaterno !== '') {
    $campos[]  = "apellido_materno = ?";
    $valores[] = $apellidoMaterno;
    $tipos    .= 's';
}

// Alias
if ($alias !== '') {
    $campos[]  = "alias = ?";
    $valores[] = $alias;
    $tipos    .= 's';
}

// Fecha de nacimiento
if ($fecha !== '') {
    $campos[]  = "fecha_de_nacimiento = ?";
    $valores[] = $fecha;
    $tipos    .= 's';
}

// Dirección (si llega lugar, lo anexamos)
if ($direccion !== '' || $lugar !== '') {
    $direccionCompleta = $direccion;
    if ($lugar !== '') {
        $direccionCompleta = trim($direccionCompleta . ' ' . $lugar);
    }
    $campos[]  = "`dirección` = ?";
    $valores[] = $direccionCompleta;
    $tipos    .= 's';
}

// Pandilla asociada
if ($id_Pandilla > 0) {
    $campos[]  = "id_Pandilla = ?";
    $valores[] = $id_Pandilla;
    $tipos    .= 'i';
}

// Peligrosidad
if ($peligrosidad > 0) {
    $campos[]  = "peligrosidad = ?";
    $valores[] = $peligrosidad;
    $tipos    .= 'i';
}

if (empty($campos)) {
    echo "No se proporcionaron datos para actualizar.";
    echo "<br><br><a href='SubmenuIntegrantes.html'>Volver al Menú Principal</a>";
    exit();
}

$sql = "UPDATE integrante SET " . implode(', ', $campos) . " WHERE id_Integrante = ?";

$valores[] = $id_Integrante;
$tipos    .= 'i';

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo "Error al preparar la consulta: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

mysqli_stmt_bind_param($stmt, $tipos, ...$valores);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error al actualizar: " . htmlspecialchars(mysqli_stmt_error($stmt));
} else {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "No se encontró un integrante con ese ID o no hubo cambios.";
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);

echo "<br><br><a href='SubmenuIntegrantes.html'>Volver al Menú Principal</a>";
