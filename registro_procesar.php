<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$_SESSION['tab_activa'] = 'register';

$tipoCuenta = $_POST['tipo_cuenta'] ?? 'cliente';
if (!in_array($tipoCuenta, ['cliente', 'negocio'], true)) {
    $tipoCuenta = 'cliente';
}
$_SESSION['tipo_registro'] = $tipoCuenta;

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

$nombreNegocio = trim($_POST['nombre_negocio'] ?? '');
$categoriaId = $_POST['categoria_id'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');

$errores = [];

if ($nombre === '') $errores[] = 'El nombre es obligatorio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido';
if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres';
if ($password !== $passwordConfirm) $errores[] = 'Las contraseñas no coinciden';

if ($tipoCuenta === 'negocio') {
    if ($nombreNegocio === '') $errores[] = 'El nombre del negocio es obligatorio';
    if ($categoriaId === '') $errores[] = 'Debe seleccionar una categoría';
    if ($direccion === '') $errores[] = 'La dirección es obligatoria';
    if ($telefono === '') $errores[] = 'El teléfono es obligatorio';
}

if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    header('Location: login.php#register');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['errores_registro'] = ['Ese email ya está registrado'];
    header('Location: login.php#register');
    exit;
}

try {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($tipoCuenta === 'negocio') {

        $pdo->beginTransaction();

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

    } else {

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
        $stmt->execute([$nombre, $email, $passwordHash]);
        $usuarioId = $pdo->lastInsertId();

        $_SESSION['usuario_id'] = $usuarioId;
        $_SESSION['rol'] = 'cliente';
        $_SESSION['nombre'] = $nombre;

        header('Location: panel/perfil/perfil_usuario.php');
        exit;

    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['errores_registro'] = ['Ocurrió un error al registrar. Intente de nuevo.'];
    header('Location: login.php#register');
    exit;
}
