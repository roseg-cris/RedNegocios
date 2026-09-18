<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

requerirProveedor();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../panel/productos/crear_producto_negocio.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT id FROM negocios WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$negocio = $stmt->fetch();

if (!$negocio) {
    header('Location: ../../login_negocio.php');
    exit;
}

$negocioId = $negocio['id'];

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoriaId = $_POST['categoria_id'] ?? '';
$subcategoriaId = $_POST['subcategoria_id'] ?? '';
$precio = $_POST['precio'] ?? '';
$precioOferta = trim($_POST['precio_oferta'] ?? '');
$stock = $_POST['stock'] ?? '0';
$esOferta = isset($_POST['es_oferta']) ? 1 : 0;
$esDestacado = isset($_POST['es_destacado']) ? 1 : 0;

$errores = [];

if ($nombre === '') $errores[] = 'El nombre del producto es obligatorio';
if ($categoriaId === '') $errores[] = 'Debe seleccionar una categoría';
if ($subcategoriaId === '') $errores[] = 'Debe seleccionar una subcategoría';
if (!is_numeric($precio) || $precio < 0) $errores[] = 'El precio no es válido';
if ($precioOferta !== '' && (!is_numeric($precioOferta) || $precioOferta < 0)) $errores[] = 'El precio de oferta no es válido';
if ($precioOferta !== '' && is_numeric($precioOferta) && is_numeric($precio) && $precioOferta >= $precio) $errores[] = 'El precio de oferta debe ser menor al precio normal';
if (!is_numeric($stock) || $stock < 0) $errores[] = 'El stock no es válido';

if ($categoriaId !== '' && $subcategoriaId !== '') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM subcategorias WHERE id = ? AND categoria_id = ?");
    $stmt->execute([$subcategoriaId, $categoriaId]);
    if ($stmt->fetchColumn() == 0) {
        $errores[] = 'La subcategoría no pertenece a la categoría seleccionada';
    }
}

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header('Location: ../../panel/productos/crear_producto_negocio.php');
    exit;
}

$precioOferta = $precioOferta === '' ? null : $precioOferta;

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO productos (negocio_id, categoria_id, subcategoria_id, nombre, descripcion, precio, precio_oferta, stock, es_oferta, es_destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$negocioId, $categoriaId, $subcategoriaId, $nombre, $descripcion, $precio, $precioOferta, $stock, $esOferta, $esDestacado]);
    $productoId = $pdo->lastInsertId();

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        $directorioProducto = __DIR__ . '/../../uploads/productos/' . $productoId;
        $resultado = procesarImagen($_FILES['imagen'], $directorioProducto, 'principal.jpg', 800, 800);

        if (!$resultado['ok']) {
            throw new Exception($resultado['error']);
        }

        $rutaImagen = $productoId . '/' . $resultado['archivo'];

        $stmt = $pdo->prepare("INSERT INTO producto_imagenes (producto_id, ruta_imagen, es_principal) VALUES (?, ?, 1)");
        $stmt->execute([$productoId, $rutaImagen]);
    }

    $pdo->commit();

    $_SESSION['exito'] = 'Producto agregado correctamente';
    header('Location: ../../panel/productos/productos_negocio.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['errores'] = ['Ocurrió un error al guardar el producto: ' . $e->getMessage()];
    header('Location: ../../panel/productos/crear_producto_negocio.php');
    exit;
}