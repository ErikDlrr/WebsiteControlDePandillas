<?php
session_start();

// Ajusta la ruta a tu conexión real
require __DIR__ . '/../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cambiar_contra.html');
    exit();
}

$id_usuario = $_SESSION['id_Usuario'] ?? $_SESSION['id_usuario'] ?? null;

if ($id_usuario === null) {
    header('Location: ../../index.html');
    exit();
}

$contraseñaActual = $_POST['contraseñaActual'] ?? '';
$nuevaContraseña  = $_POST['nuevaContraseña'] ?? '';

if ($contraseñaActual === '' || $nuevaContraseña === '') {
    header('Location: cambiar_contra.html?err=datos');
    exit();
}

// 1. Obtener contraseña actual desde la BD
$sqlSelect = "SELECT contraseña FROM usuarios WHERE id_Usuario = ?";
$stmt = mysqli_prepare($conexion, $sqlSelect);

if (!$stmt) {
    error_log('Error prepare (select) cambiar_contrasena.php: ' . mysqli_error($conexion));
    header('Location: cambiar_contra.html?err=internal');
    exit();
}

mysqli_stmt_bind_param($stmt, 'i', $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    // Usuario no encontrado
    header('Location: cambiar_contra.html?err=user');
    exit();
}

$row = mysqli_fetch_assoc($result);
$hashActual = $row['contraseña'];

// 2. Verificar contraseña actual
$valida = false;

// Si ya está hasheada
if (password_verify($contraseñaActual, $hashActual)) {
    $valida = true;
} elseif (hash_equals($hashActual, $contraseñaActual)) {
    $valida = true;
}

if (!$valida) {
    header('Location: cambiar_contra.html?err=actual');
    exit();
}

// 3. Actualizar a la nueva contraseña 
$nuevoHash = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

$sqlUpdate = "UPDATE usuarios SET contraseña = ? WHERE id_Usuario = ?";
$stmtUpdate = mysqli_prepare($conexion, $sqlUpdate);

if (!$stmtUpdate) {
    error_log('Error prepare (update) cambiar_contrasena.php: ' . mysqli_error($conexion));
    header('Location: cambiar_contra.html?err=internal');
    exit();
}

mysqli_stmt_bind_param($stmtUpdate, 'si', $nuevoHash, $id_usuario);

if (!mysqli_stmt_execute($stmtUpdate)) {
    error_log('Error execute (update) cambiar_contrasena.php: ' . mysqli_error($conexion));
    header('Location: cambiar_contra.html?err=internal');
    exit();
}

// Éxito
header('Location: cambiar_contra.html?ok=1');
exit();
