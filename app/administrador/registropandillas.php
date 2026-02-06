<?php
// registropandillas.php
// Inserta una nueva pandilla en la tabla `pandillas`

require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: CapturaPandillas.html');
    exit();
}

// Sanitizar/normalizar entradas
$nombre               = trim($_POST['nombre'] ?? '');
$descripcion          = trim($_POST['descripcion'] ?? '');
$lider                = trim($_POST['lider'] ?? '');
$numeroIntegrantes    = isset($_POST['numero_aproximado_de_integrantes']) && is_numeric($_POST['numero_aproximado_de_integrantes'])
                        ? (int)$_POST['numero_aproximado_de_integrantes']
                        : 0;
$edadesAproximadas    = trim($_POST['edades_aproximadas'] ?? '');
$perfilRedSocial      = trim($_POST['perfil_Red_Social'] ?? '');
$horario              = trim($_POST['Horario_de_reunion'] ?? '');
$peligrosidad         = trim($_POST['peligrosidad'] ?? '');
$direccion            = trim($_POST['direccion'] ?? '');
$fechaAniversario     = trim($_POST['fecha_de_aniversario'] ?? '');

// Validaciones mínimas
if ($nombre === '' || $descripcion === '' || $lider === '') {
    echo "Faltan datos obligatorios (nombre, descripción o líder). ";
    echo "<a href='CapturaPandillas.html'>Volver</a>";
    exit();
}

// 1. Insertar dirección en la tabla `ubicacion`
// Asumimos que el string de dirección va al campo `calle`
$sqlUbicacion = "INSERT INTO ubicacion (calle) VALUES (?)";
$stmtUbicacion = mysqli_prepare($conexion, $sqlUbicacion);

if (!$stmtUbicacion) {
    echo "Error al preparar consulta de ubicación: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

mysqli_stmt_bind_param($stmtUbicacion, 's', $direccion);

if (!mysqli_stmt_execute($stmtUbicacion)) {
    echo "Error al insertar ubicación: " . htmlspecialchars(mysqli_stmt_error($stmtUbicacion));
    exit();
}

$idDireccion = mysqli_insert_id($conexion);
mysqli_stmt_close($stmtUbicacion);

// 2. Insertar pandilla usando el ID de la dirección
$sql = "
    INSERT INTO pandillas (
        nombre,
        descripcion,
        lider,
        numero_aproximado_de_integrantes,
        edades_aproximadas,
        perfil_Red_Social,
        Horario_de_reunion,
        peligrosidad,
        direccion,
        fecha_de_aniversario
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo "Error al preparar la consulta de pandilla: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

// Tipos:
// s = string
// i = integer
// direccion ahora es 'i' (integer)
$tipos = 'sssissssis';

mysqli_stmt_bind_param(
    $stmt,
    $tipos,
    $nombre,
    $descripcion,
    $lider,
    $numeroIntegrantes,
    $edadesAproximadas,
    $perfilRedSocial,
    $horario,
    $peligrosidad,
    $idDireccion,
    $fechaAniversario
);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error al insertar pandilla: " . htmlspecialchars(mysqli_stmt_error($stmt));
} else {
    $idPandilla = mysqli_insert_id($conexion);
    
    // 3. Actualizar la ubicación con el ID de la pandilla
    $sqlUpdateUbicacion = "UPDATE ubicacion SET id_Pandilla = ? WHERE id_direccion = ?";
    $stmtUpdate = mysqli_prepare($conexion, $sqlUpdateUbicacion);
    if ($stmtUpdate) {
        mysqli_stmt_bind_param($stmtUpdate, 'ii', $idPandilla, $idDireccion);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);
    }

    echo "Registro exitoso. <a href='CapturaPandillas.html'>Volver</a>";
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
