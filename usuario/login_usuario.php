<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (clienteAutenticado()) {
    header('Location: ../panel/perfil/perfil_usuario.php');
    exit;
}

$erroresLogin = $_SESSION['errores_login'] ?? [];
$erroresRegistro = [];
$tabActiva = 'sign-in';
unset($_SESSION['errores_login']);

require __DIR__ . '/../includes/vista-login-registro-usuario.php';
