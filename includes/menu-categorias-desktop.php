<?php

$iconosCategoria = [
    'Casa y jardín' => 'icon-home3',
    'Joyería' => 'icon-diamond2',
    'Computación y tecnología' => 'icon-desktop',
    'Accesorios' => 'icon-bag',
    'Ferretería' => 'icon-hammer-wrench',
    'Plomería' => 'icon-drop',
    'Electricidad' => 'icon-flashlight',
    'Herramientas' => 'icon-screwdriver',
];

$categoriasMenu = $pdo->query(
    "SELECT c.id, c.nombre,
            s.id AS sub_id, s.nombre AS sub_nombre
     FROM categorias c
     LEFT JOIN subcategorias s ON s.categoria_id = c.id AND s.estado = 'activo'
     WHERE c.estado = 'activo'
     ORDER BY c.nombre, s.nombre"
)->fetchAll();

$categoriasAgrupadas = [];
foreach ($categoriasMenu as $fila) {
    $catId = $fila['id'];
    if (!isset($categoriasAgrupadas[$catId])) {
        $categoriasAgrupadas[$catId] = [
            'nombre' => $fila['nombre'],
            'subcategorias' => [],
        ];
    }
    if ($fila['sub_id']) {
        $categoriasAgrupadas[$catId]['subcategorias'][] = [
            'id' => $fila['sub_id'],
            'nombre' => $fila['sub_nombre'],
        ];
    }
}
?>
<div class="menu--product-categories">

    <div class="menu__toggle">
    	<i class="icon-menu"></i>
    	<span> Categorías</span>
    </div>

    <div class="menu__content">
        <ul class="menu--dropdown">
            <?php foreach ($categoriasAgrupadas as $categoria): ?>
            <?php $tieneSubcategorias = !empty($categoria['subcategorias']); ?>
            <li class="<?= $tieneSubcategorias ? 'has-mega-menu' : '' ?>">
            	<a href="#"><i class="<?= $iconosCategoria[$categoria['nombre']] ?? 'icon-tag' ?>"></i> <?= htmlspecialchars($categoria['nombre']) ?></a>
                <?php if ($tieneSubcategorias): ?>
                <div class="mega-menu">
                    <div class="mega-menu__column">
                        <h4><?= htmlspecialchars($categoria['nombre']) ?></h4>
                        <ul class="mega-menu__list">
                            <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                            <li><a href="#"><?= htmlspecialchars($subcategoria['nombre']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>

</div>