<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (proveedorAutenticado()) {
    header('Location: panel/perfil/perfil_negocio.php');
    exit;
}

$categorias = $pdo->query("SELECT id, nombre FROM categorias WHERE estado = 'activo' ORDER BY nombre")->fetchAll();

$erroresLogin = [];
$erroresRegistro = $_SESSION['errores_registro'] ?? [];
$tabActiva = 'register';
unset($_SESSION['errores_registro']);

require __DIR__ . '/includes/vista-login-registro-negocio.php';
