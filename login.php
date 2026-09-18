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

$categorias = $pdo->query("SELECT id, nombre FROM categorias WHERE estado = 'activo' ORDER BY nombre")->fetchAll();

$erroresLogin = $_SESSION['errores_login'] ?? [];
$erroresRegistro = $_SESSION['errores_registro'] ?? [];
$tabActiva = $_SESSION['tab_activa'] ?? 'sign-in';
$tipoRegistro = $_SESSION['tipo_registro'] ?? 'cliente';
unset($_SESSION['errores_login'], $_SESSION['errores_registro'], $_SESSION['tab_activa'], $_SESSION['tipo_registro']);

require __DIR__ . '/includes/vista-login-unificado.php';
