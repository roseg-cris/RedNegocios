<?php
session_start();

/**
 * Calcula la ruta base del proyecto dentro del servidor web,
 * comparando la carpeta física del proyecto (un nivel arriba de
 * includes/) contra el DOCUMENT_ROOT del servidor.
 *
 * Si el proyecto vive en la raíz de htdocs, devuelve "".
 * Si vive en una subcarpeta (ej. /RedNegocios-trabajo), devuelve
 * "/RedNegocios-trabajo". Así los redirects funcionan igual sin
 * importar dónde esté desplegado el proyecto.
 */
function baseUrlProyecto() {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));

    if ($projectRoot === false || $docRoot === false || strpos($projectRoot, $docRoot) !== 0) {
        return '';
    }

    $base = substr($projectRoot, strlen($docRoot));
    return rtrim($base, '/');
}

function proveedorAutenticado() {
    return isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'negocio';
}

function requerirProveedor() {
    if (!proveedorAutenticado()) {
        header('Location: ' . baseUrlProyecto() . '/login_negocio.php');
        exit;
    }
}

function clienteAutenticado() {
    return isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'cliente';
}

function requerirCliente() {
    if (!clienteAutenticado()) {
        header('Location: ' . baseUrlProyecto() . '/usuario/login_usuario.php');
        exit;
    }
}
