<?php
function generarSlug($texto, PDO $pdo) {
    $slug = strtolower(trim($texto));
    $slug = preg_replace('/[áàäâ]/u', 'a', $slug);
    $slug = preg_replace('/[éèëê]/u', 'e', $slug);
    $slug = preg_replace('/[íìïî]/u', 'i', $slug);
    $slug = preg_replace('/[óòöô]/u', 'o', $slug);
    $slug = preg_replace('/[úùüû]/u', 'u', $slug);
    $slug = preg_replace('/ñ/u', 'n', $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    $slugBase = $slug;
    $contador = 1;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM negocios WHERE slug = ?");

    while (true) {
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            break;
        }
        $slug = $slugBase . '-' . $contador;
        $contador++;
    }

    return $slug;
}

function procesarImagen(array $archivo, string $directorioDestino, string $nombreArchivo, int $anchoDestino, int $altoDestino) {
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tamanoMaximo = 5 * 1024 * 1024;

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Ocurrió un error al subir el archivo'];
    }

    if ($archivo['size'] > $tamanoMaximo) {
        return ['ok' => false, 'error' => 'La imagen no debe pesar más de 5 MB'];
    }

    $infoImagen = getimagesize($archivo['tmp_name']);
    if ($infoImagen === false) {
        return ['ok' => false, 'error' => 'El archivo no es una imagen válida'];
    }

    $tipoMime = $infoImagen['mime'];
    if (!in_array($tipoMime, $tiposPermitidos, true)) {
        return ['ok' => false, 'error' => 'Solo se permiten imágenes JPG, PNG o WEBP'];
    }

    switch ($tipoMime) {
        case 'image/jpeg':
            $imagenOrigen = imagecreatefromjpeg($archivo['tmp_name']);
            break;
        case 'image/png':
            $imagenOrigen = imagecreatefrompng($archivo['tmp_name']);
            break;
        case 'image/webp':
            $imagenOrigen = imagecreatefromwebp($archivo['tmp_name']);
            break;
        default:
            return ['ok' => false, 'error' => 'Formato de imagen no soportado'];
    }

    if (!$imagenOrigen) {
        return ['ok' => false, 'error' => 'No se pudo procesar la imagen'];
    }

    $anchoOrigen = imagesx($imagenOrigen);
    $altoOrigen = imagesy($imagenOrigen);

    $escala = max($anchoDestino / $anchoOrigen, $altoDestino / $altoOrigen);
    $anchoRecorte = (int) round($anchoDestino / $escala);
    $altoRecorte = (int) round($altoDestino / $escala);
    $recorteX = (int) round(($anchoOrigen - $anchoRecorte) / 2);
    $recorteY = (int) round(($altoOrigen - $altoRecorte) / 2);

    $imagenDestino = imagecreatetruecolor($anchoDestino, $altoDestino);
    imagecopyresampled(
        $imagenDestino, $imagenOrigen,
        0, 0, $recorteX, $recorteY,
        $anchoDestino, $altoDestino, $anchoRecorte, $altoRecorte
    );

    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    $rutaCompleta = rtrim($directorioDestino, '/') . '/' . $nombreArchivo;
    imagejpeg($imagenDestino, $rutaCompleta, 82);

    imagedestroy($imagenOrigen);
    imagedestroy($imagenDestino);

    return ['ok' => true, 'archivo' => $nombreArchivo];
}
