<?php
require __DIR__ . '/../php/conexion.php';

header('Content-Type: application/json');

$zona = $_GET['zona'] ?? '';

$sql = "
    SELECT 
        p.id_Pandilla,
        p.nombre,
        p.descripcion,
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.peligrosidad,
        u.latitud,
        u.longitud,
        u.zona,
        u.colonia
    FROM pandillas p
    JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
";

if ($zona !== '' && $zona !== 'todas') {
    $sql .= " WHERE u.zona = ?";
}

$stmt = mysqli_prepare($conexion, $sql);

if ($zona !== '' && $zona !== 'todas') {
    mysqli_stmt_bind_param($stmt, 's', $zona);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pandillas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pandillas[] = $row;
}

echo json_encode($pandillas);
