<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'criminal_nexus';

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

mysqli_set_charset($conexion, 'utf8mb4');
