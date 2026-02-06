<?php
require __DIR__ . '/php/conexion.php';

echo "Intentando arreglar la tabla 'antecedentes'...\n";

// 1. Verificar si ya existe la clave primaria
$checkPK = "SHOW KEYS FROM antecedentes WHERE Key_name = 'PRIMARY'";
$resultPK = mysqli_query($conexion, $checkPK);

if (mysqli_num_rows($resultPK) == 0) {
    echo "No se encontró Primary Key. Agregando...\n";
    $sqlPK = "ALTER TABLE antecedentes ADD PRIMARY KEY (id_antecedente)";
    if (mysqli_query($conexion, $sqlPK)) {
        echo "Primary Key agregada correctamente.\n";
    } else {
        echo "Error al agregar Primary Key: " . mysqli_error($conexion) . "\n";
    }
} else {
    echo "Primary Key ya existe.\n";
}

// 2. Modificar columna para ser AUTO_INCREMENT
echo "Intentando establecer AUTO_INCREMENT...\n";
$sqlAI = "ALTER TABLE antecedentes MODIFY id_antecedente int(11) NOT NULL AUTO_INCREMENT";
if (mysqli_query($conexion, $sqlAI)) {
    echo "AUTO_INCREMENT establecido correctamente.\n";
} else {
    echo "Error al establecer AUTO_INCREMENT: " . mysqli_error($conexion) . "\n";
}

echo "Proceso finalizado.\n";
?>
