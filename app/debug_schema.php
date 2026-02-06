<?php
require __DIR__ . '/php/conexion.php';

if ($conexion) {
    $query = "DESCRIBE pandillas";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        echo "Table: pandillas\n";
        echo str_pad("Field", 30) . str_pad("Type", 20) . "\n";
        echo str_repeat("-", 50) . "\n";
        while ($row = mysqli_fetch_assoc($result)) {
            echo str_pad($row['Field'], 30) . str_pad($row['Type'], 20) . "\n";
        }
    } else {
        echo "Error describing table: " . mysqli_error($conexion);
    }
    mysqli_close($conexion);
} else {
    echo "Connection failed.";
}
