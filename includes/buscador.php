<?php
/**
 * Barra de búsqueda del header (versión escritorio).
 * Requiere que $pdo ya esté disponible (config/config.php ya incluido).
 */

$categoriasBuscador = $pdo->query(
    "SELECT id, nombre FROM categorias WHERE estado = 'activo' ORDER BY nombre"
)->fetchAll();
?>
<div class="header__content-center">
    <form class="ps-form--quick-search" action="#" method="get">
        <div class="form-group--icon">
        	<i class="icon-chevron-down"></i>
            <select class="form-control" name="categoria_id">
                <option value="">Todas</option>
                <?php foreach ($categoriasBuscador as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input class="form-control" type="text" name="q" placeholder="¿Qué estás buscando?">
        <button type="submit">Buscar</button>
    </form>
</div>
