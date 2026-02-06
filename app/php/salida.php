<?php
session_start();

// Vaciar arreglo de sesión
$_SESSION = [];

// Borrar cookie de sesión si existe
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destruir sesión
session_destroy();

// Volver al login
header('Location: ../index.html');
exit();
