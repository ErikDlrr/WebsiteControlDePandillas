<?php
// desplegable.php
// Imprime <option> de todas las pandillas (id_Pandilla, nombre)

// Intentar localizar conexion.php en las rutas típicas del proyecto
if (file_exists(__DIR__ . '/../php/conexion.php')) {
    require __DIR__ . '/../php/conexion.php';
} elseif (file_exists(__DIR__ . '/php/conexion.php')) {
    require __DIR__ . '/php/conexion.php';
} else {
    die('No se encontró el archivo de conexión a la base de datos.');
}

// Consulta para obtener las pandillas
$sql = "SELECT id_Pandilla, nombre FROM pandillas ORDER BY nombre";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $id     = htmlspecialchars($fila['id_Pandilla']);
        $nombre = htmlspecialchars($fila['nombre']);
        echo "<option value=\"{$id}\">{$nombre}</option>";
    }
} else {
    echo '<option value="">No hay pandillas registradas</option>';
}

if ($resultado) {
    mysqli_free_result($resultado);
}
