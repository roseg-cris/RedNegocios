<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

requerirProveedor();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../panel/perfil/perfil_negocio.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT id, logo, portada FROM negocios WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$negocioActual = $stmt->fetch();

if (!$negocioActual) {
    header('Location: ../../login_negocio.php');
    exit;
}

$negocioId = $negocioActual['id'];

$categoriaId = $_POST['categoria_id'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

$errores = [];

if ($categoriaId === '') $errores[] = 'Debe seleccionar una categoría';
if ($direccion === '') $errores[] = 'La dirección es obligatoria';
if ($telefono === '') $errores[] = 'El teléfono es obligatorio';

$directorioNegocio = __DIR__ . '/../../uploads/negocios/' . $negocioId;

$nombreLogo = $negocioActual['logo'];
if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
    $resultado = procesarImagen($_FILES['logo'], $directorioNegocio, 'logo.jpg', 400, 400);
    if (!$resultado['ok']) {
        $errores[] = 'Logo: ' . $resultado['error'];
    } else {
        $nombreLogo = $negocioId . '/' . $resultado['archivo'];
    }
}

$nombrePortada = $negocioActual['portada'];
if (isset($_FILES['portada']) && $_FILES['portada']['error'] !== UPLOAD_ERR_NO_FILE) {
    $resultado = procesarImagen($_FILES['portada'], $directorioNegocio, 'portada.jpg', 1200, 400);
    if (!$resultado['ok']) {
        $errores[] = 'Portada: ' . $resultado['error'];
    } else {
        $nombrePortada = $negocioId . '/' . $resultado['archivo'];
    }
}

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header('Location: ../../panel/perfil/editar_perfil_negocio.php');
    exit;
}

$stmt = $pdo->prepare("UPDATE negocios SET categoria_id = ?, direccion = ?, telefono = ?, whatsapp = ?, descripcion = ?, logo = ?, portada = ? WHERE usuario_id = ?");
$stmt->execute([$categoriaId, $direccion, $telefono, $whatsapp, $descripcion, $nombreLogo, $nombrePortada, $usuarioId]);

$_SESSION['exito'] = 'Tus datos se actualizaron correctamente';
header('Location: ../../panel/perfil/perfil_negocio.php');
exit;
