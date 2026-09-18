<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro_negocio.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$nombreNegocio = trim($_POST['nombre_negocio'] ?? '');
$categoriaId = $_POST['categoria_id'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');

$errores = [];

if ($nombre === '') $errores[] = 'El nombre es obligatorio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido';
if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres';
if ($nombreNegocio === '') $errores[] = 'El nombre del negocio es obligatorio';
if ($categoriaId === '') $errores[] = 'Debe seleccionar una categoría';
if ($direccion === '') $errores[] = 'La dirección es obligatoria';
if ($telefono === '') $errores[] = 'El teléfono es obligatorio';

if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    header('Location: registro_negocio.php#register');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['errores_registro'] = ['Ese email ya está registrado'];
    header('Location: registro_negocio.php#register');
    exit;
}

try {
    $pdo->beginTransaction();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'negocio')");
    $stmt->execute([$nombre, $email, $passwordHash]);
    $usuarioId = $pdo->lastInsertId();

    $slug = generarSlug($nombreNegocio, $pdo);

    $stmt = $pdo->prepare("INSERT INTO negocios (usuario_id, categoria_id, nombre, slug, direccion, telefono, whatsapp, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$usuarioId, $categoriaId, $nombreNegocio, $slug, $direccion, $telefono, $whatsapp, $email]);

    $pdo->commit();

    $_SESSION['usuario_id'] = $usuarioId;
    $_SESSION['rol'] = 'negocio';
    $_SESSION['nombre'] = $nombre;
    $_SESSION['registro_pendiente'] = true;

    header('Location: panel/perfil/perfil_negocio.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['errores_registro'] = ['Ocurrió un error al registrar. Intente de nuevo.'];
    header('Location: registro_negocio.php#register');
    exit;
}
