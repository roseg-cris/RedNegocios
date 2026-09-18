<?php
session_start();

function proveedorAutenticado() {
    return isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'negocio';
}

function requerirProveedor() {
    if (!proveedorAutenticado()) {
        header('Location: /login_negocio.php');
        exit;
    }
}

function clienteAutenticado() {
    return isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'cliente';
}

function requerirCliente() {
    if (!clienteAutenticado()) {
        header('Location: /login_usuario.php');
        exit;
    }
}
