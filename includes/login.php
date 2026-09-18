<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (proveedorAutenticado()) {
    header('Location: panel/perfil/perfil_negocio.php');
    exit;
}

if (clienteAutenticado()) {
    header('Location: panel/perfil/perfil_usuario.php');
    exit;
}

$erroresLogin = $_SESSION['errores_login'] ?? [];
unset($_SESSION['errores_login']);

require __DIR__ . '/includes/vista-login-unificado.php';
