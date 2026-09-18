<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (clienteAutenticado()) {
    header('Location: panel/perfil/perfil_usuario.php');
    exit;
}

$erroresLogin = [];
$erroresRegistro = $_SESSION['errores_registro'] ?? [];
$tabActiva = 'register';
unset($_SESSION['errores_registro']);

require __DIR__ . '/includes/vista-login-registro-usuario.php';
