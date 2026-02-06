<?php
// actualizar_pandillas_handler.php
session_start();

// Restringir a consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}

require __DIR__ . '/../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: actualizar_pandillas.php');
    exit();
}

$id_Pandilla = isset($_POST['id_Pandilla']) ? (int)$_POST['id_Pandilla'] : 0;

if ($id_Pandilla <= 0) {
    echo "ERROR: Debes proporcionar un ID válido.";
    exit();
}

// Recibir datos opcionales
$nombre              = trim($_POST['nombre'] ?? '');
$descripcion         = trim($_POST['descripcion'] ?? '');
$lider               = trim($_POST['lider'] ?? '');
$numeroIntegrantes   = trim($_POST['numero_integrantes'] ?? '');
$edadesAprox         = trim($_POST['edades_aproximadas'] ?? '');
$perfilRed           = trim($_POST['perfil_red_social'] ?? '');
$edades              = trim($_POST['edades'] ?? '');
$horario             = trim($_POST['horario_reunion'] ?? '');
$peligrosidad        = trim($_POST['peligrosidad'] ?? '');
$direccion           = trim($_POST['direccion'] ?? '');
$fechaAniversario    = trim($_POST['fecha_aniversario'] ?? '');

// Armado dinámico del UPDATE
$campos  = [];
$valores = [];
$tipos   = '';

// Se agregan solo los campos que NO estén vacíos
function agregarCampo(&$campos, &$valores, &$tipos, $campoBD, $valor) {
    if ($valor !== '') {
        $campos[]  = "$campoBD = ?";
        $valores[] = $valor;
        $tipos    .= 's';
    }
}

// Campos comunes
agregarCampo($campos, $valores, $tipos, 'nombre', $nombre);
agregarCampo($campos, $valores, $tipos, 'descripcion', $descripcion);
agregarCampo($campos, $valores, $tipos, 'lider', $lider);
agregarCampo($campos, $valores, $tipos, 'numero_aproximado_de_integrantes', $numeroIntegrantes);
agregarCampo($campos, $valores, $tipos, 'edades_aproximadas', $edadesAprox);
agregarCampo($campos, $valores, $tipos, 'perfil_Red_Social', $perfilRed);
agregarCampo($campos, $valores, $tipos, 'edades', $edades);
agregarCampo($campos, $valores, $tipos, 'Horario_de_reunion', $horario);
agregarCampo($campos, $valores, $tipos, 'peligrosidad', $peligrosidad);
agregarCampo($campos, $valores, $tipos, 'direccion', $direccion);
agregarCampo($campos, $valores, $tipos, 'fecha_de_aniversario', $fechaAniversario);

if (empty($campos)) {
    echo "No se envió ningún dato para actualizar.";
    echo "<br><br><a href='actualizar_pandillas.php'>Volver</a>";
    exit();
}

// Completar sql
$sql = "UPDATE pandillas SET " . implode(', ', $campos) . " WHERE id_Pandilla = ?";
$valores[] = $id_Pandilla;
$tipos    .= 'i';

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo "Error prepare: " . mysqli_error($conexion);
    exit();
}

mysqli_stmt_bind_param($stmt, $tipos, ...$valores);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Actualización exitosa.";
} else {
    echo "No hubo cambios o el ID no existe.";
}

echo "<br><br><a href='consultor.php'>Volver al Panel</a>";
