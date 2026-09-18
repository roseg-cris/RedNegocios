<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

requerirCliente();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../panel/perfil/perfil_usuario.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwordActual = $_POST['password_actual'] ?? '';
$passwordNueva = $_POST['password_nueva'] ?? '';
$passwordConfirmar = $_POST['password_confirmar'] ?? '';

$errores = [];

if ($nombre === '') $errores[] = 'El nombre es obligatorio';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido';

// ¿El email ya lo usa otro usuario?
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id <> ?");
    $stmt->execute([$email, $usuarioId]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = 'Ese email ya está en uso por otra cuenta';
    }
}

$cambiaPassword = ($passwordActual !== '' || $passwordNueva !== '' || $passwordConfirmar !== '');
$nuevoHash = null;

if ($cambiaPassword) {
    if ($passwordActual === '') {
        $errores[] = 'Debes ingresar tu contraseña actual para cambiarla';
    } else {
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
        $hashActual = $stmt->fetchColumn();

        if (!$hashActual || !password_verify($passwordActual, $hashActual)) {
            $errores[] = 'La contraseña actual no es correcta';
        }
    }

    if (strlen($passwordNueva) < 8) {
        $errores[] = 'La nueva contraseña debe tener al menos 8 caracteres';
    } elseif ($passwordNueva !== $passwordConfirmar) {
        $errores[] = 'La confirmación de la nueva contraseña no coincide';
    } else {
        $nuevoHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
    }
}

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header('Location: ../../panel/perfil/editar_perfil_usuario.php');
    exit;
}

if ($nuevoHash !== null) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?");
    $stmt->execute([$nombre, $email, $nuevoHash, $usuarioId]);
} else {
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
    $stmt->execute([$nombre, $email, $usuarioId]);
}

$_SESSION['nombre'] = $nombre;
$_SESSION['exito'] = 'Tus datos se actualizaron correctamente';
header('Location: ../../panel/perfil/perfil_usuario.php');
exit;
