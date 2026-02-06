<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.html');
    exit();
}

require __DIR__ . '/conexion.php';

$usuario    = isset($_POST['usuario'])    ? trim($_POST['usuario'])    : '';
$contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

if ($usuario === '' || $contrasena === '') {
    header('Location: ../index.html?err=datos');
    exit();
}

$sql = "SELECT 
            id_Usuario,
            nombre_Usuario,
            tipo_Usuario,
            contraseña
        FROM usuarios
        WHERE nombre_Usuario = ?
        LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log('Error en prepare(): ' . mysqli_error($conexion));
    header('Location: ../index.html?err=internal');
    exit();
}

mysqli_stmt_bind_param($stmt, 's', $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    // Usuario no encontrado
    header('Location: ../index.html?err=user');
    exit();
}

$fila = mysqli_fetch_assoc($result);

// Verificación de contraseña (hash o texto plano, para no tronar nada)
$passCorrecta = false;

if (password_verify($contrasena, $fila['contraseña'])) {
    $passCorrecta = true;
} elseif (hash_equals($fila['contraseña'], $contrasena)) {
    // Fallback por si todavía guardas en texto plano
    $passCorrecta = true;
}

if (!$passCorrecta) {
    header('Location: ../index.html?err=pass');
    exit();
}

// Login correcto
session_regenerate_id(true);

$_SESSION['id_Usuario']   = $fila['id_Usuario'];
$_SESSION['usuario']      = $fila['nombre_Usuario'];
$_SESSION['tipo_usuario'] = $fila['tipo_Usuario'];

// Redirección según tipo_Usuario
switch ($fila['tipo_Usuario']) {
    case 'administrador':
        header('Location: ../home.php');
        break;

    case 'consultor':
        header('Location: ../home1.php');
        break;

    case 'ciudadano':
        header('Location: ../ciudadano/mCuidadano.html');
        break;

    default:
        header('Location: ../index.html?err=rol');
        break;
}

exit();
