<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_usuario.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['errores_login'] = ['Email y contraseña son obligatorios'];
    header('Location: login_usuario.php#sign-in');
    exit;
}

$stmt = $pdo->prepare("SELECT id, nombre, password, rol, estado FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if (!$usuario || !password_verify($password, $usuario['password'])) {
    $_SESSION['errores_login'] = ['Email o contraseña incorrectos'];
    header('Location: login_usuario.php#sign-in');
    exit;
}

if ($usuario['estado'] !== 'activo') {
    $_SESSION['errores_login'] = ['Tu cuenta está ' . $usuario['estado'] . '. Contacta al administrador.'];
    header('Location: login_usuario.php#sign-in');
    exit;
}

$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['rol'] = $usuario['rol'];
$_SESSION['nombre'] = $usuario['nombre'];

// Se acepta cualquier cuenta (cliente o negocio) y se manda al panel que le corresponda,
// para que no quede atrapado si llega aquí con una cuenta del otro tipo.
if ($usuario['rol'] === 'negocio') {
    header('Location: ../panel/perfil/perfil_negocio.php');
} else {
    header('Location: ../panel/perfil/perfil_usuario.php');
}
exit;
