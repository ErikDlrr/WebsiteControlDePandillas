<?php
// iniciodesesion.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit();
}

require __DIR__ . '/php/conexion.php';

// Recibir datos del login
$usuario    = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['password'] ?? '');

if ($usuario === '' || $contrasena === '') {
    header('Location: index.html?err=datos');
    exit();
}

// Consulta real según la estructura de tu tabla
$sql = "SELECT id_Usuario, nombre_Usuario, tipo_Usuario, contraseña
        FROM usuarios
        WHERE nombre_Usuario = ?
        LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log("Error prepare login: " . mysqli_error($conexion));
    header('Location: index.html?err=internal');
    exit();
}

mysqli_stmt_bind_param($stmt, 's', $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: index.html?err=user');
    exit();
}

$row = mysqli_fetch_assoc($result);

// Validación de contraseña con hash o texto plano
$valida = false;

if (password_verify($contrasena, $row['contraseña'])) {
    $valida = true;
} elseif (hash_equals($row['contraseña'], $contrasena)) {
    // ← compatibilidad si aún tienes contraseñas sin hash
    $valida = true;
}

if (!$valida) {
    header('Location: index.html?err=pass');
    exit();
}

// Login correcto
session_regenerate_id(true);

$_SESSION['id_Usuario']   = $row['id_Usuario'];
$_SESSION['usuario']      = $row['nombre_Usuario'];
$_SESSION['tipo_usuario'] = $row['tipo_Usuario'];

// Redirección según rol
switch ($row['tipo_Usuario']) {
    case 'administrador':
        header('Location: home.php');
        break;

    case 'consultor':
        header('Location: home1.php');
        break;

    case 'ciudadano':
        header('Location: ciudadano/mCuidadano.html');
        break;

    default:
        header('Location: index.html?err=rol');
}

exit();
