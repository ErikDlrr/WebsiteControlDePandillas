<?php
// actualizarusuario.php
require __DIR__ . '/../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ActualizarUsuarios.php');
    exit();
}

$id_Usuario = isset($_POST['id_Usuario']) ? (int)$_POST['id_Usuario'] : 0;

if ($id_Usuario <= 0) {
    echo "ERROR: ID de usuario inválido.";
    exit();
}

// Campos opcionales
$nombreUsuario = trim($_POST['nombre_usuario'] ?? '');
$tipoUsuario   = trim($_POST['tipo_usuario'] ?? '');
$nombre        = trim($_POST['nombre'] ?? '');
$apellido      = trim($_POST['apellido'] ?? '');
$puesto        = trim($_POST['puesto'] ?? '');
$email         = trim($_POST['email'] ?? '');
$contrasena    = trim($_POST['contraseña'] ?? '');

// Construir UPDATE dinámico
$campos  = [];
$valores = [];
$tipos   = '';

function addField(&$campos, &$valores, &$tipos, $col, $val, $tipoChar = 's') {
    if ($val !== '') {
        $campos[]  = "$col = ?";
        $valores[] = $val;
        $tipos    .= $tipoChar;
    }
}

addField($campos, $valores, $tipos, 'nombre_Usuario', $nombreUsuario);
addField($campos, $valores, $tipos, 'tipo_Usuario', $tipoUsuario);
addField($campos, $valores, $tipos, 'Nombre', $nombre);
addField($campos, $valores, $tipos, 'Apellido', $apellido);
addField($campos, $valores, $tipos, 'Puesto', $puesto);
addField($campos, $valores, $tipos, 'Email', $email);

// Contraseña: si se envía, la hashéamos
if ($contrasena !== '') {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    addField($campos, $valores, $tipos, 'contraseña', $hash);
}

if (empty($campos)) {
    echo "No se envió ningún dato para actualizar.";
    echo "<br><br><a href='ActualizarUsuarios.php'>Volver</a>";
    exit();
}

$sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id_Usuario = ?";
$valores[] = $id_Usuario;
$tipos    .= 'i';

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
    echo "Error al preparar la consulta: " . mysqli_error($conexion);
    exit();
}

mysqli_stmt_bind_param($stmt, $tipos, ...$valores);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "No hubo cambios o el ID no existe.";
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);

echo "<br><br><a href='SubmenuUsuarios.html'>Volver al menú de usuarios</a>";
