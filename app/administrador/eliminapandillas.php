<?php
// eliminapandillas.php
// Elimina pandillas por nombre (usado desde EliminarPandillas.html)

require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: EliminarPandillas.html');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');

if ($nombre === '') {
    echo "ERROR: Debes proporcionar el nombre de la pandilla.";
    echo "<br><br><a href='EliminarPandillas.html'>Volver</a>";
    exit();
}

// Primero verificamos si existe alguna pandilla con ese nombre
$checkSql = "SELECT id_Pandilla FROM pandillas WHERE nombre = ? LIMIT 1";
$checkStmt = mysqli_prepare($conexion, $checkSql);

if (!$checkStmt) {
    echo "Error al preparar la consulta de verificación: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

mysqli_stmt_bind_param($checkStmt, 's', $nombre);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
    echo "No se encontró una pandilla con el nombre '" . htmlspecialchars($nombre) . "'.";
    echo "<br><br><a href='EliminarPandillas.html'>Volver</a>";
    exit();
}

mysqli_stmt_close($checkStmt);

// Ahora sí, borramos todas las pandillas con ese nombre
$deleteSql = "DELETE FROM pandillas WHERE nombre = ?";
$deleteStmt = mysqli_prepare($conexion, $deleteSql);

if (!$deleteStmt) {
    echo "Error al preparar la eliminación: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

mysqli_stmt_bind_param($deleteStmt, 's', $nombre);
mysqli_stmt_execute($deleteStmt);

if (mysqli_stmt_affected_rows($deleteStmt) > 0) {
    echo "La(s) pandilla(s) con nombre '" . htmlspecialchars($nombre) . "' ha(n) sido eliminada(s) correctamente.";
} else {
    echo "No se pudo eliminar la pandilla. Puede que ya no exista.";
}

mysqli_stmt_close($deleteStmt);
mysqli_close($conexion);

echo "<br><br><a href='SubmenuPandillas.html'>Volver al Menú Principal</a>";
