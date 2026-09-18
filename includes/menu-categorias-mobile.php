<?php

$categoriasMenuMovil = $pdo->query(
    "SELECT c.id, c.nombre,
            s.id AS sub_id, s.nombre AS sub_nombre
     FROM categorias c
     LEFT JOIN subcategorias s ON s.categoria_id = c.id AND s.estado = 'activo'
     WHERE c.estado = 'activo'
     ORDER BY c.nombre, s.nombre"
)->fetchAll();

$categoriasAgrupadasMovil = [];
foreach ($categoriasMenuMovil as $fila) {
    $catId = $fila['id'];
    if (!isset($categoriasAgrupadasMovil[$catId])) {
        $categoriasAgrupadasMovil[$catId] = [
            'nombre' => $fila['nombre'],
            'subcategorias' => [],
        ];
    }
    if ($fila['sub_id']) {
        $categoriasAgrupadasMovil[$catId]['subcategorias'][] = [
            'id' => $fila['sub_id'],
            'nombre' => $fila['sub_nombre'],
        ];
    }
}
?>
<div class="menu--product-categories">

    <div class="ps-shop__filter-mb mt-4" id="filter-sidebar">
    	<i class="icon-menu "></i>
    </div>

	<div class="ps-filter--sidebar">

	    <div class="ps-filter__header">
	        <h3>Categorías</h3><a class="ps-btn--close ps-btn--no-boder" href="#"></a>
	    </div>

	    <div class="ps-filter__content">

	        <aside class="widget widget_shop">

	            <ul class="ps-list--categories">
	                <?php foreach ($categoriasAgrupadasMovil as $categoria): ?>
	                <?php $tieneSubcategorias = !empty($categoria['subcategorias']); ?>
	                <li class="<?= $tieneSubcategorias ? 'menu-item-has-children' : '' ?>">
	                	<a href="#"><?= htmlspecialchars($categoria['nombre']) ?></a>
	                	<?php if ($tieneSubcategorias): ?>
	                	<span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
	                    <ul class="sub-menu" style="display: none;">
	                        <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
	                        <li><a href="#"><?= htmlspecialchars($subcategoria['nombre']) ?></a></li>
	                        <?php endforeach; ?>
	                    </ul>
	                    <?php endif; ?>
	                </li>
	                <?php endforeach; ?>
	            </ul>

	        </aside>

	    </div>

	</div>

</div>
