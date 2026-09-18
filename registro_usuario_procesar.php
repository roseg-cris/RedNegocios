<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro_usuario.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

$errores = [];

if ($nombre === '') $errores[] = 'El nombre es obligatorio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido';
if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres';
if ($password !== $passwordConfirm) $errores[] = 'Las contraseñas no coinciden';

if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    header('Location: registro_usuario.php#register');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['errores_registro'] = ['Ese email ya está registrado'];
    header('Location: registro_usuario.php#register');
    exit;
}

try {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
    $stmt->execute([$nombre, $email, $passwordHash]);
    $usuarioId = $pdo->lastInsertId();

    $_SESSION['usuario_id'] = $usuarioId;
    $_SESSION['rol'] = 'cliente';
    $_SESSION['nombre'] = $nombre;

    header('Location: panel/perfil/perfil_usuario.php');
    exit;

} catch (Exception $e) {
    $_SESSION['errores_registro'] = ['Ocurrió un error al registrar. Intente de nuevo.'];
    header('Location: registro_usuario.php#register');
    exit;
}
