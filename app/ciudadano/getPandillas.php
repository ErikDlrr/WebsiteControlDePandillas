<?php
require __DIR__ . '/../php/conexion.php';

// Configurar cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Obtener la zona seleccionada desde la URL
$zona = isset($_GET['zona']) ? $_GET['zona'] : 'todas';

$sql = "
    SELECT 
        u.id_direccion, 
        u.id_Pandilla, 
        p.nombre AS nombre_pandilla, 
        p.descripcion AS descripcion_pandilla, 
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.peligrosidad,
        u.latitud, 
        u.longitud, 
        u.punto_reunion, 
        u.calle, 
        u.numero_de_calle, 
        u.entre_calles, 
        u.colonia, 
        u.localidad, 
        u.zona, 
        u.municipio
    FROM 
        ubicacion u
    INNER JOIN 
        pandillas p ON u.id_Pandilla = p.id_Pandilla
";

if ($zona !== 'todas') {
    $sql .= " WHERE u.zona = ?";
}

$stmt = mysqli_prepare($conexion, $sql);

if ($zona !== 'todas') {
    mysqli_stmt_bind_param($stmt, 's', $zona);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pandillas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pandillas[] = $row;
}

echo json_encode($pandillas);
