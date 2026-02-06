<?php
require __DIR__ . '/../php/conexion.php';

$sql = "ALTER TABLE integrante ADD COLUMN peligrosidad INT(11) DEFAULT NULL";

if (mysqli_query($conexion, $sql)) {
    echo "Columna 'peligrosidad' agregada correctamente.";
} else {
    echo "Error al agregar columna: " . mysqli_error($conexion);
}

mysqli_close($conexion);
