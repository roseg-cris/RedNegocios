<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

requerirProveedor();

$usuarioId = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT n.*, c.nombre AS categoria_nombre FROM negocios n JOIN categorias c ON c.id = n.categoria_id WHERE n.usuario_id = ?");
$stmt->execute([$usuarioId]);
$negocio = $stmt->fetch();

if (!$negocio) {
    session_unset();
    session_destroy();
    header('Location: ../../login_negocio.php');
    exit;
}

$exito = $_SESSION['exito'] ?? null;
unset($_SESSION['exito']);

$mostrarPendiente = ($negocio['estado'] === 'pendiente');

$categorias = $pdo->query("SELECT id, nombre FROM categorias WHERE estado = 'activo' ORDER BY nombre")->fetchAll();
$subcategorias = $pdo->query("SELECT id, categoria_id, nombre FROM subcategorias WHERE estado = 'activo' ORDER BY nombre")->fetchAll();

$subcategoriasPorCategoria = [];
foreach ($subcategorias as $sub) {
    $subcategoriasPorCategoria[$sub['categoria_id']][] = $sub;
}

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

	<title>RedNegocios | Nuevo producto</title>

	<link rel="icon" href="../../assets/img/template/icono.png">

	<!--=====================================
	CSS
	======================================-->
	
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&display=swap" rel="stylesheet">

	<!-- font awesome -->
	<link rel="stylesheet" href="../../assets/css/plugins/fontawesome.min.css">

	<!-- linear icons -->
	<link rel="stylesheet" href="../../assets/css/plugins/linearIcons.css">

	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="../../assets/css/plugins/owl.carousel.css">

	<!-- Slick -->
	<link rel="stylesheet" href="../../assets/css/plugins/slick.css">

	<!-- Light Gallery -->
	<link rel="stylesheet" href="../../assets/css/plugins/lightgallery.min.css">

	<!-- Font Awesome Start -->
	<link rel="stylesheet" href="../../assets/css/plugins/fontawesome-stars.css">

	<!-- jquery Ui -->
	<link rel="stylesheet" href="../../assets/css/plugins/jquery-ui.min.css">

	<!-- Select 2 -->
	<link rel="stylesheet" href="../../assets/css/plugins/select2.min.css">

	<!-- Scroll Up -->
	<link rel="stylesheet" href="../../assets/css/plugins/scrollUp.css">

    <!-- DataTable -->
    <link rel="stylesheet" href="../../assets/css/plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../assets/css/plugins/responsive.bootstrap.datatable.min.css">
	
	<!-- estilo principal -->
	<link rel="stylesheet" href="../../assets/css/style.css">

	<!-- Market Place 4 -->
	<link rel="stylesheet" href="../../assets/css/market-place-4.css">

	<!--=====================================
	PLUGINS JS
	======================================-->

	<!-- jQuery library -->
	<script src="../../assets/js/plugins/jquery-1.12.4.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<!-- Owl Carousel -->
	<script src="../../assets/js/plugins/owl.carousel.min.js"></script>

	<!-- Images Loaded -->
	<script src="../../assets/js/plugins/imagesloaded.pkgd.min.js"></script>

	<!-- Masonry -->
	<script src="../../assets/js/plugins/masonry.pkgd.min.js"></script>

	<!-- Isotope -->
	<script src="../../assets/js/plugins/isotope.pkgd.min.js"></script>

	<!-- jQuery Match Height -->
	<script src="../../assets/js/plugins/jquery.matchHeight-min.js"></script>

	<!-- Slick -->
	<script src="../../assets/js/plugins/slick.min.js"></script>

	<!-- jQuery Barrating -->
	<script src="../../assets/js/plugins/jquery.barrating.min.js"></script>

	<!-- Slick Animation -->
	<script src="../../assets/js/plugins/slick-animation.min.js"></script>

	<!-- Light Gallery -->
	<script src="../../assets/js/plugins/lightgallery-all.min.js"></script>

	<!-- jQuery UI -->
	<script src="../../assets/js/plugins/jquery-ui.min.js"></script>

	<!-- Sticky Sidebar -->
	<script src="../../assets/js/plugins/sticky-sidebar.min.js"></script>

	<!-- Slim Scroll -->
	<script src="../../assets/js/plugins/jquery.slimscroll.min.js"></script>

	<!-- Select 2 -->
	<script src="../../assets/js/plugins/select2.full.min.js"></script>

	<!-- Scroll Up -->
	<script src="../../assets/js/plugins/scrollUP.js"></script>

    <!-- DataTable -->
    <script src="../../assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/js/plugins/dataTables.responsive.min.js"></script>
	
</head>

<body>

	<!--=====================================
	Header Promotion
	======================================-->

	<div class="ps-block--promotion-header bg--cover" style="background: url(../../assets/img/banner/top/header-promotion.jpg);">
        <div class="container">
            <div class="ps-block__left">
                <h3>20%</h3>
                <figure>
                    <p>Discount</p>
                    <h4>For Books Of March</h4>
                </figure>
            </div>
            <div class="ps-block__center">
                <p>Enter Promotion<span>Sale2019</span></p>
            </div><a class="ps-btn ps-btn--sm" href="#">Shop now</a>
        </div>
    </div>

    <!--=====================================
	Header
	======================================-->

    <header class="header header--standard header--market-place-4" data-sticky="true">

    	<!--=====================================
		Header TOP
		======================================-->

        <div class="header__top">

            <div class="container">

            	<!--=====================================
				Social 
				======================================-->

                <div class="header__left">
                    <ul class="d-flex justify-content-center">
						<li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
						<li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>					
						<li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
						<li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
					</ul>
                </div>

                <!--=====================================
				Contact & lenguage 
				======================================-->

                <div class="header__right">
                    <ul class="header__top-links"> 
                    	<li><i class="icon-telephone"></i> Contacto:<strong> 1-800-234-5678</strong></li>                     
                        <li>
                            <div class="ps-dropdown language"><a href="#"><img src="../../assets/img/template/es.png" alt="">Español</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div><!-- End Container -->

        </div><!-- Header Top -->

      	<!--=====================================
		Header Content
		======================================-->

        <div class="header__content">

            <div class="container">

                <div class="header__content-left">

                	<!--=====================================
					Logo
					======================================-->

                	<a class="ps-logo" href="../../index.php">
                		<img src="../../assets/img/template/logo_light.png" alt="">
                	</a>

                	<!--=====================================
					Menú
					======================================-->

                    <div class="menu--product-categories">
                        
                        <div class="menu__toggle">
                        	<i class="icon-menu"></i>
                        	<span> Categorías</span>
                        </div>

                        <div class="menu__content">
                            <ul class="menu--dropdown">
                                <li>
                                	<a href="#"><i class="icon-star"></i> Hot Promotions</a>
                                </li>
                                <li class="menu-item-has-children has-mega-menu">
                                	<a href="#"><i class="icon-laundry"></i> Consumer Electronic</a>
                                    <div class="mega-menu">
                                        <div class="mega-menu__column">
                                            <h4>Electronic<span class="sub-toggle"></span></h4>
                                            <ul class="mega-menu__list">
                                                <li><a href="#">Home Audio &amp; Theathers</a>
                                                </li>
                                                <li><a href="#">TV &amp; Videos</a>
                                                </li>
                                                <li><a href="#">Camera, Photos &amp; Videos</a>
                                                </li>
                                                <li><a href="#">Cellphones &amp; Accessories</a>
                                                </li>
                                                <li><a href="#">Headphones</a>
                                                </li>
                                                <li><a href="#">Videosgames</a>
                                                </li>
                                                <li><a href="#">Wireless Speakers</a>
                                                </li>
                                                <li><a href="#">Office Electronic</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="mega-menu__column">
                                            <h4>Accessories &amp; Parts<span class="sub-toggle"></span></h4>
                                            <ul class="mega-menu__list">
                                                <li><a href="#">Digital Cables</a>
                                                </li>
                                                <li><a href="#">Audio &amp; Video Cables</a>
                                                </li>
                                                <li><a href="#">Batteries</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-shirt"></i> Clothing &amp; Apparel</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-lampshade"></i> Home, Garden &amp; Kitchen</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-heart-pulse"></i> Health &amp; Beauty</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-diamond2"></i> Yewelry &amp; Watches</a>
                                </li>
                                <li class="menu-item-has-children has-mega-menu">
                                	<a href="#"><i class="icon-desktop"></i> Computer &amp; Technology</a>
                                    <div class="mega-menu">
                                        <div class="mega-menu__column">
                                            <h4>Computer &amp; Technologies<span class="sub-toggle"></span></h4>
                                            <ul class="mega-menu__list">
                                                <li><a href="#">Computer &amp; Tablets</a>
                                                </li>
                                                <li><a href="#">Laptop</a>
                                                </li>
                                                <li><a href="#">Monitors</a>
                                                </li>
                                                <li><a href="#">Networking</a>
                                                </li>
                                                <li><a href="#">Drive &amp; Storages</a>
                                                </li>
                                                <li><a href="#">Computer Components</a>
                                                </li>
                                                <li><a href="#">Security &amp; Protection</a>
                                                </li>
                                                <li><a href="#">Gaming Laptop</a>
                                                </li>
                                                <li><a href="#">Accessories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-baby-bottle"></i> Babies &amp; Moms</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-baseball"></i> Sport &amp; Outdoor</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-smartphone"></i> Phones &amp; Accessories</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-book2"></i> Books &amp; Office</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-car-siren"></i> Cars &amp; Motocycles</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-wrench"></i> Home Improments</a>
                                </li>
                                <li>
                                	<a href="#"><i class="icon-tag"></i> Vouchers &amp; Services</a>
                                </li>
                            </ul>

                        </div>

                    </div><!-- End menu-->

                </div><!-- End Header Content Left-->

                <div class="header__content-right">

                    <div class="header__actions">

						<!--=====================================
						Mensajes y Notificaciones
						======================================-->

						<a class="header__extra" href="#">
							<i class="icon-envelope"></i><span><i>0</i></span>
						</a>

						<a class="header__extra" href="#">
							<i class="fas fa-bell"></i><span><i>0</i></span>
						</a>

						<!--=====================================
						Login and Register
						======================================-->

                        <div class="ps-block--user-header">
                            <div class="ps-block__left">
                            	<img class="img-fluid rounded-circle w-50 ml-auto" src="<?= !empty($negocio['logo']) ? '../../uploads/negocios/' . htmlspecialchars($negocio['logo']) . '?v=' . strtotime($negocio['updated_at']) : '../../assets/img/vendor/store/user/5.jpg' ?>" alt="">
                            </div>
                            <div class="ps-block__right">
                            	<a href="../../panel/perfil/perfil_negocio.php">Mi cuenta</a>
                            	
                            </div>
                        </div>

                    </div><!-- End Header Actions-->

                </div><!-- End Header Content Right-->

            </div><!-- End Container-->

        </div><!-- End Header Content-->

    </header>

  	<!--=====================================
	Header Mobile
	======================================-->

    <header class="header header--mobile" data-sticky="true">

        <div class="header__top">

            <div class="header__left">

                <ul class="d-flex justify-content-center">
					<li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
					<li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>					
					<li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
					<li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
				</ul>
            </div>

            <div class="header__right">

                <ul class="navigation__extra">

                   <li><i class="icon-telephone"></i> Contacto:<strong> 1-800-234-5678</strong></li>    

                    <li>

                        <div class="ps-dropdown language"><a href="#"><img src="../../assets/img/template/es.png" alt="">Español</a>
                        </div>

                    </li>

                </ul>

            </div>

        </div>

        <div class="navigation--mobile">

            <div class="navigation__left">

        	  	<!--=====================================
				Menu Mobile
				======================================-->

                <div class="menu--product-categories">
                    
                    <div class="ps-shop__filter-mb mt-4" id="filter-sidebar">
                    	<i class="icon-menu "></i>
                    </div>

	            	<div class="ps-filter--sidebar">

					    <div class="ps-filter__header">
					        <h3>Categories</h3><a class="ps-btn--close ps-btn--no-boder" href="#"></a>
					    </div>

					    <div class="ps-filter__content">

					        <aside class="widget widget_shop">

					            <ul class="ps-list--categories">
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Clothing &amp; Apparel</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="display: none;">
					                        <li class="current-menu-item "><a href="shop-default.html">Womens</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Mens</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Bags</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Sunglasses</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Accessories</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Kid's Fashion</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Garden &amp; Kitchen</a><span class="sub-toggle active"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="display: block;">
					                        <li class="current-menu-item "><a href="shop-default.html">Cookware</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Decoration</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Furniture</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Garden Tools</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Home Improvement</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Powers And Hand Tools</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Utensil &amp; Gadget</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Consumer Electrics</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Air Conditioners</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">Accessories</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Type Hanging Cell</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Type Hanging Wall</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Audios &amp; Theaters</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">Headphone</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Home Theater System</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Speakers</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Car Electronics</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">Audio &amp; Video</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Car Security</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Radar Detector</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Vehicle GPS</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Office Electronics</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">Printers</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Projectors</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Scanners</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Store &amp; Business</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">TV Televisions</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">4K Ultra HD TVs</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">LED TVs</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">OLED TVs</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Washing Machines</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                            <ul class="sub-menu" style="">
					                                <li class="current-menu-item "><a href="shop-default.html">Type Drying Clothes</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Type Horizontal</a>
					                                </li>
					                                <li class="current-menu-item "><a href="shop-default.html">Type Vertical</a>
					                                </li>
					                            </ul>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Refrigerators</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Health &amp; Beauty</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item "><a href="shop-default.html">Equipments</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Hair Care</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Perfumer</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Skin Care</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Computers &amp; Technologies</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item "><a href="shop-default.html">Desktop PC</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Laptop</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Smartphones</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Jewelry &amp; Watches</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item "><a href="shop-default.html">Gemstone Jewelry</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Men's Watches</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Women's Watches</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Phones &amp; Accessories</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item "><a href="shop-default.html">Iphone 8</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Iphone X</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Sam Sung Note 8</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Sam Sung S8</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item menu-item-has-children"><a href="shop-default.html">Sport &amp; Outdoor</a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
					                    <ul class="sub-menu" style="">
					                        <li class="current-menu-item "><a href="shop-default.html">Freezer Burn</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Fridge Cooler</a>
					                        </li>
					                        <li class="current-menu-item "><a href="shop-default.html">Wine Cabinets</a>
					                        </li>
					                    </ul>
					                </li>
					                <li class="current-menu-item "><a href="shop-default.html">Babies &amp; Moms</a>
					                </li>
					                <li class="current-menu-item "><a href="shop-default.html">Books &amp; Office</a>
					                </li>
					                <li class="current-menu-item "><a href="shop-default.html">Cars &amp; Motocycles</a>
					                </li>
					            </ul>

					        </aside>   

					    </div>

					</div>        

                </div><!-- End menu-->

            </div>

            <div class="navigation__center">

            	<a class="ps-logo" href="../../index.php">
            		<img src="../../assets/img/template/logo_light.png" class="pt-3" alt="">
            	</a>
            </div>

            <div class="navigation__right">

                <div class="header__actions">

                    <!--=====================================
					Mensajes y Notificaciones
					======================================-->

                    <a class="header__extra" href="#">
                    	<i class="icon-envelope"></i><span><i>0</i></span>
                    </a>

                    <a class="header__extra" href="#">
                    	<i class="fas fa-bell"></i><span><i>0</i></span>
                    </a>

                    <!--=====================================
					Login and Register
					======================================-->

                    <div class="ps-block--user-header">

                        <div class="ps-block__left">
                        	<i class="icon-user"></i>
                        </div>
                        <div class="ps-block__right">
                        	<a href="../../panel/perfil/perfil_negocio.php">Mi cuenta</a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header> <!-- End Header Mobile -->

    <!--=====================================
    Breadcrumb
    ======================================-->  
	
	<div class="ps-breadcrumb">

        <div class="container">

            <ul class="breadcrumb">

                <li><a href="../../index.php">Inicio</a></li>

                <li><a href="../../panel/perfil/perfil_negocio.php">Mi cuenta</a></li>

                <li><a href="../../panel/productos/productos_negocio.php">Mis productos</a></li>

                <li>Nuevo producto</li>

            </ul>

            <a href="../../logout_negocio.php" class="float-right">Cerrar sesión</a>

        </div>

    </div>

    <!--=====================================
    My Account Content
    ======================================--> 

    <div class="ps-vendor-dashboard pro">

        <div class="container">

            <div class="ps-section__header">

                <!--=====================================
                Profile
                ======================================--> 

                <aside class="ps-block--store-banner">

                    <div class="ps-block__user" <?= !empty($negocio['portada']) ? 'style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url(\'../../uploads/negocios/' . htmlspecialchars($negocio['portada']) . '?v=' . strtotime($negocio['updated_at']) . '\'); background-size: cover; background-position: center;"' : '' ?>>

                        <div class="ps-block__user-avatar">

                            <img src="<?= !empty($negocio['logo']) ? '../../uploads/negocios/' . htmlspecialchars($negocio['logo']) . '?v=' . strtotime($negocio['updated_at']) : '../../assets/img/vendor/store/user/5.jpg' ?>" alt="">

                        </div>

                        <div class="ps-block__user-content text-center text-lg-left">

                            <h2 class="text-white"><?= htmlspecialchars($negocio['nombre']) ?></h2>

                            <p><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></p>

                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($negocio['email'] ?? '') ?></p>

                            <?php if ($mostrarPendiente): ?>
                            <span class="badge badge-warning">Pendiente de aprobación</span>
                            <?php else: ?>
                            <span class="badge badge-success">Activo</span>
                            <?php endif; ?>

                        </div>

                        <div class="row ml-lg-auto pt-5">

                            <div class="col-lg-4 col-6">
                                <div class="text-center">
                                    <h1><i class="fas fa-store text-white"></i></h1>
                                    <h4 class="text-white"><?= htmlspecialchars($negocio['categoria_nombre']) ?></h4>
                                </div>
                            </div><!-- box /-->

                            <div class="col-lg-4 col-6">
                                <div class="text-center">
                                    <h1><i class="fas fa-phone text-white"></i></h1>
                                    <h4 class="text-white"><?= htmlspecialchars($negocio['telefono']) ?></h4>
                                </div>
                            </div><!-- box /-->

                            <?php if (!empty($negocio['whatsapp'])): ?>
                            <div class="col-lg-4 col-6">
                                <div class="text-center">
                                    <h1><i class="fab fa-whatsapp text-white"></i></h1>
                                    <h4 class="text-white"><?= htmlspecialchars($negocio['whatsapp']) ?></h4>
                                </div>
                            </div><!-- box /-->
                            <?php endif; ?>
                        </div>

                    </div>

                </aside><!-- s -->

                <!--=====================================
                Nav Account
                ======================================--> 
   
                <div class="ps-section__content">

                    <ul class="ps-section__links">
                        <li><a href="../../panel/perfil/perfil_negocio.php">Mi perfil</a></li>
                        <li><a href="../../panel/perfil/editar_perfil_negocio.php">Editar perfil</a></li>
                        <li class="active"><a href="../../panel/productos/productos_negocio.php">Mis productos</a></li>
                    </ul>

                <div class="ps-page__content row">

                    <div class="container">

                        <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errores as $error): ?>
                            <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <h1 class="mb-4">Nuevo producto</h1>

                        <form action="../../panel/productos/crear_producto_negocio_procesar.php" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="nombre">Nombre del producto</label>
                                <input class="form-control" type="text" id="nombre" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="categoria_id">Categoría</label>
                                    <select class="form-control" id="categoria_id" name="categoria_id" required>
                                        <option value="" selected disabled>Selecciona una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="subcategoria_id">Subcategoría</label>
                                    <select class="form-control" id="subcategoria_id" name="subcategoria_id" required disabled>
                                        <option value="" selected>Primero elige una categoría</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label for="precio">Precio</label>
                                    <input class="form-control" type="number" step="0.01" min="0" id="precio" name="precio" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="precio_oferta">Precio de oferta (opcional)</label>
                                    <input class="form-control" type="number" step="0.01" min="0" id="precio_oferta" name="precio_oferta">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="stock">Stock</label>
                                    <input class="form-control" type="number" min="0" id="stock" name="stock" value="0" required>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Imagen principal</label>
                                <input class="form-control-file" type="file" name="imagen" accept="image/jpeg,image/png,image/webp">
                                <small class="form-text text-muted">JPG, PNG o WEBP, máximo 5 MB. Opcional, se puede agregar después.</small>
                            </div>

                            <div class="form-group">
                                <div class="ps-checkbox">
                                    <input class="form-control" type="checkbox" id="es_oferta" name="es_oferta" value="1">
                                    <label for="es_oferta">Marcar como oferta</label>
                                </div>
                                <div class="ps-checkbox">
                                    <input class="form-control" type="checkbox" id="es_destacado" name="es_destacado" value="1">
                                    <label for="es_destacado">Marcar como destacado</label>
                                </div>
                            </div>

                            <div class="form-group submtit d-flex justify-content-end" style="gap: 12px;">
                                <a href="../../panel/productos/productos_negocio.php" class="ps-btn ps-btn--black">Cancelar</a>
                                <button type="submit" class="ps-btn">Guardar producto</button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
    var subcategoriasPorCategoria = <?= json_encode($subcategoriasPorCategoria, JSON_UNESCAPED_UNICODE) ?>;

    document.getElementById('categoria_id').addEventListener('change', function () {
        var subSelect = document.getElementById('subcategoria_id');
        var lista = subcategoriasPorCategoria[this.value] || [];

        subSelect.innerHTML = '';

        if (lista.length === 0) {
            subSelect.innerHTML = '<option value="" selected>No hay subcategorías para esta categoría</option>';
            subSelect.disabled = true;
            return;
        }

        var opcionInicial = document.createElement('option');
        opcionInicial.value = '';
        opcionInicial.selected = true;
        opcionInicial.disabled = true;
        opcionInicial.textContent = 'Selecciona una subcategoría';
        subSelect.appendChild(opcionInicial);

        lista.forEach(function (sub) {
            var opcion = document.createElement('option');
            opcion.value = sub.id;
            opcion.textContent = sub.nombre;
            subSelect.appendChild(opcion);
        });

        subSelect.disabled = false;
    });
    </script>

    <!--=====================================
	Footer
	======================================-->  

    <footer class="ps-footer">

        <div class="container">

            <div class="ps-footer__widgets">

            	<!--=====================================
				Contacto
				======================================-->  

                <aside class="widget widget_footer widget_contact-us">

                    <h4 class="widget-title">Contacto</h4>

                    <div class="widget_content">

                        <p>Atención al cliente</p>
                        <h3>1800 97 97 69</h3>
                        <p>
                        	<a href="mailto:contact@marketplace.co">contact@marketplace.co</a>
                    	</p>

                        <ul class="ps-list--social">
                            <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
                        </ul>

                    </div>

                </aside>

                <!--=====================================
				Enlaces rapidos
				======================================-->  

                <aside class="widget widget_footer">

                    <h4 class="widget-title">Enlaces rápidos</h4>

                    <ul class="ps-list--link">

                        <li><a href="#">Política de privacidad</a></li>

                        <li><a href="#">Términos y condiciones</a></li>

                        <li><a href="../../faqs.html">Preguntas frecuentes</a></li>

                    </ul>

                </aside>

                <!--=====================================
				Empresa
				======================================-->  

                <aside class="widget widget_footer">

                    <h4 class="widget-title">Empresa</h4>

                    <ul class="ps-list--link">

                        <li><a href="../../about-us.html">Sobre nosotros</a></li>

                        <li><a href="../../contact-us.html">Contacto</a></li>

                    </ul>

                </aside>

            </div>

            <!--=====================================
			CopyRight Footer
			======================================-->  

            <div class="ps-footer__copyright">

                <p>© 2026 RedNegocios. Todos los derechos reservados</p>

            </div>

        </div>

    </footer>


	<!--=====================================
	JS PERSONALIZADO
	======================================-->

	<script src="../../assets/js/main.js"></script>
	
</body>
</html>