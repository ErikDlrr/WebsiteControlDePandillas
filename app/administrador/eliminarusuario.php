<?php
// eliminarusuario.php
require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: EliminarUsuarios.php');
    exit();
}

$id_Usuario = isset($_POST['id_Usuario']) ? (int)$_POST['id_Usuario'] : 0;

if ($id_Usuario <= 0) {
    echo "ERROR: ID de usuario inválido.";
    exit();
}

// Verificar primero
$check = mysqli_prepare($conexion, "SELECT nombre_Usuario FROM usuarios WHERE id_Usuario = ? LIMIT 1");
mysqli_stmt_bind_param($check, 'i', $id_Usuario);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "No se encontró un usuario con ese ID.";
    echo "<br><br><a href='EliminarUsuarios.php'>Volver</a>";
    exit();
}
$row = mysqli_fetch_assoc($res);
$usuarioNombre = $row['nombre_Usuario'] ?? '';
mysqli_free_result($res);
mysqli_stmt_close($check);

// Eliminar
$del = mysqli_prepare($conexion, "DELETE FROM usuarios WHERE id_Usuario = ?");
mysqli_stmt_bind_param($del, 'i', $id_Usuario);
mysqli_stmt_execute($del);

if (mysqli_stmt_affected_rows($del) > 0) {
    echo "Usuario '" . htmlspecialchars($usuarioNombre) . "' eliminado correctamente.";
} else {
    echo "No se pudo eliminar el usuario. Puede que ya no exista.";
}

mysqli_stmt_close($del);
mysqli_close($conexion);

echo "<br><br><a href='SubmenuUsuarios.html'>Volver al menú de usuarios</a>";
