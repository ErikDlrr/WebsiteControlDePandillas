<?php
// ingreso/insertar.php
// Alta de nuevos usuarios del sistema

require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: addUser.html');
    exit();
}

// Leer datos del formulario (soportamos nombres alternativos por si acaso)
$usuario     = trim($_POST['nombre_usuario'] ?? $_POST['usuario'] ?? '');
$passPlano   = trim($_POST['contraseña'] ?? $_POST['password'] ?? '');
$tipo        = trim($_POST['tipo_usuario'] ?? '');
$nombre      = trim($_POST['nombre'] ?? '');
$apellido    = trim($_POST['apellido'] ?? '');
$puesto      = trim($_POST['puesto'] ?? '');
$email       = trim($_POST['email'] ?? '');

if ($usuario === '' || $passPlano === '' || $tipo === '') {
    echo "Faltan datos obligatorios (usuario, contraseña o tipo).";
    echo "<br><br><a href='addUser.html'>Volver</a>";
    exit();
}

// 1) Obtener siguiente id_Usuario (porque la columna no tiene AUTO_INCREMENT)
$nextId = 1;
$sqlId  = "SELECT COALESCE(MAX(id_Usuario), 0) + 1 AS next_id FROM usuarios";
if ($resId = mysqli_query($conexion, $sqlId)) {
    if ($rowId = mysqli_fetch_assoc($resId)) {
        $nextId = (int)$rowId['next_id'];
    }
    mysqli_free_result($resId);
} else {
    echo "Error al calcular el siguiente ID de usuario: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

// 2) Preparar el INSERT incluyendo id_Usuario
$sql = "
    INSERT INTO usuarios (
        id_Usuario,
        nombre_Usuario,
        contraseña,
        tipo_Usuario,
        Nombre,
        Apellido,
        Puesto,
        Email
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo "Error al preparar la consulta: " . htmlspecialchars(mysqli_error($conexion));
    exit();
}

// Hash de la contraseña (mejor que guardarla en texto plano)
$hash = password_hash($passPlano, PASSWORD_DEFAULT);

// Tipos: i = int, s = string
mysqli_stmt_bind_param(
    $stmt,
    'isssssss',
    $nextId,
    $usuario,
    $hash,
    $tipo,
    $nombre,
    $apellido,
    $puesto,
    $email
);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error al insertar usuario: " . htmlspecialchars(mysqli_stmt_error($stmt));
} else {
    echo "Usuario registrado correctamente.";
    echo "<br><br><a href='addUser.html'>Registrar otro usuario</a>";
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);

