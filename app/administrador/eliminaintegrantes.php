<?php
// eliminaintegrantes.php
require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: EliminarIntegrantes.html');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');

if ($nombre === '') {
    echo "ERROR: Debes proporcionar un nombre.";
    exit();
}

// Buscar si existe para mostrar error correcto
$check = mysqli_prepare($conexion,
        "SELECT id_Integrante FROM integrante WHERE nombre = ? LIMIT 1");
mysqli_stmt_bind_param($check, 's', $nombre);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "No existe un integrante con ese nombre.";
    echo "<br><a href='EliminarIntegrantes.html'>Volver</a>";
    exit();
}

// DELETE real
$sql = "DELETE FROM integrante WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 's', $nombre);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Integrante eliminado correctamente.";
} else {
    echo "No se pudo eliminar. Puede que ya no exista.";
}

echo "<br><br><a href='SubmenuIntegrantes.html'>Volver al menú</a>";
