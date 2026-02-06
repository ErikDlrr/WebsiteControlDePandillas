<?php
header('Content-Type: application/json');

require __DIR__ . '/../../php/conexion.php';

$zona = $_GET['zona'] ?? 'todas';
$zona = trim($zona);

$sql = "
    SELECT 
        p.nombre,
        p.descripcion,
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.peligrosidad,
        u.latitud,
        u.longitud,
        u.colonia,
        u.zona
    FROM pandillas p
    JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
";

$params = [];
$types  = '';

if ($zona !== '' && $zona !== 'todas') {
    $sql .= " WHERE u.zona = ?";
    $params[] = $zona;
    $types   .= 's';
}

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log('Error prepare obtener_pandillas.php: ' . mysqli_error($conexion));
    echo json_encode([]);
    exit();
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

if (!mysqli_stmt_execute($stmt)) {
    error_log('Error execute obtener_pandillas.php: ' . mysqli_error($conexion));
    echo json_encode([]);
    exit();
}

$result = mysqli_stmt_get_result($stmt);

$pandillas = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pandillas[] = $row;
    }
}

echo json_encode($pandillas);
