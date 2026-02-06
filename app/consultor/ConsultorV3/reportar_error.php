<?php
require __DIR__ . '/../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form_error.html');
    exit();
}

// Soportar nombres viejos y nuevos de campos
$nombre = $_POST['nombre'] ?? $_POST['nombreSoporte'] ?? '';
$email  = $_POST['email'] ?? $_POST['emailSoporte'] ?? '';
$descripcion = $_POST['descripcion'] ?? $_POST['descripcionSoporte'] ?? '';

$nombre = trim($nombre);
$email = trim($email);
$descripcion = trim($descripcion);

if ($descripcion === '') {
    header('Location: form_error.html?err=datos');
    exit();
}

$fechaGeneracion = date('Y-m-d');

// Tu tabla reportes: id_Pandilla e id_Integrante se quedan NULL
$sql = "INSERT INTO reportes (id_Pandilla, id_Integrante, tipo_Reporte, fecha_Generacion)
        VALUES (NULL, NULL, ?, ?)";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log('Error prepare reportar_error.php: ' . mysqli_error($conexion));
    header('Location: form_error.html?err=internal');
    exit();
}

mysqli_stmt_bind_param($stmt, 'ss', $descripcion, $fechaGeneracion);

if (!mysqli_stmt_execute($stmt)) {
    error_log('Error execute reportar_error.php: ' . mysqli_error($conexion));
    header('Location: form_error.html?err=internal');
    exit();
}

header('Location: form_error.html?ok=1');
exit();
