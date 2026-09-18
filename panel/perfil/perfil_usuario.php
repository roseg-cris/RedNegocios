<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

requerirCliente();

$usuarioId = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT id, nombre, email, rol, estado, created_at FROM usuarios WHERE id = ? AND rol = 'cliente'");
$stmt->execute([$usuarioId]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_unset();
    session_destroy();
    header('Location: ../../usuario/login_usuario.php');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM favoritos WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$totalFavoritos = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes_producto WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$totalLikes = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM comentarios WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$totalComentarios = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM conversaciones WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$totalConversaciones = (int) $stmt->fetchColumn();

$exito = $_SESSION['exito'] ?? null;
unset($_SESSION['exito']);

$estadoBadge = [
    'activo' => 'badge-success',
    'inactivo' => 'badge-secondary',
    'bloqueado' => 'badge-danger',
][$usuario['estado']] ?? 'badge-secondary';
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

	<title>RedNegocios | Mi Perfil</title>

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

                    <?php include __DIR__ . '/../../includes/menu-categorias-desktop.php'; ?>

                </div><!-- End Header Content Left-->

                <?php include __DIR__ . '/../../includes/buscador.php'; ?>


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
                            	<img class="img-fluid rounded-circle w-50 ml-auto" src="../../assets/img/users/1.jpg" alt="">
                            </div>
                            <div class="ps-block__right">
                            	<a href="../../panel/perfil/perfil_usuario.php">Mi cuenta</a>
                            	<a href="../../usuario/logout_usuario.php">Cerrar sesión</a>
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

                <?php include __DIR__ . '/../../includes/menu-categorias-mobile.php'; ?>

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
                        	<a href="../../panel/perfil/perfil_usuario.php">Mi cuenta</a>
                        	<a href="../../usuario/logout_usuario.php">Cerrar sesión</a>
                        </div>

                    </div>

                </div>

            </div>

        </div>


    <?php include __DIR__ . '/../../includes/buscador-mobile.php'; ?>
    </header> <!-- End Header Mobile -->

    <!--=====================================
    Breadcrumb
    ======================================-->  
	
	<div class="ps-breadcrumb">

        <div class="container">

            <ul class="breadcrumb">

                <li><a href="../../index.php">Inicio</a></li>

                <li>Mi cuenta</li>

            </ul>

            <a href="../../usuario/logout_usuario.php" class="float-right">Cerrar sesión</a>

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

                    <div class="ps-block__user">

                        <div class="ps-block__user-avatar">

                            <img src="../../assets/img/users/1.jpg" alt="">

                        </div>

                        <div class="ps-block__user-content text-center text-lg-left">

                            <h2 class="text-white"><?= htmlspecialchars($usuario['nombre']) ?></h2>

                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($usuario['email']) ?></p>

                            <p><i class="fas fa-calendar-alt"></i> Cliente desde <?= htmlspecialchars(date('d/m/Y', strtotime($usuario['created_at']))) ?></p>

                            <span class="badge <?= $estadoBadge ?>"><?= htmlspecialchars(ucfirst($usuario['estado'])) ?></span>

                        </div>

                        <div class="row ml-lg-auto pt-5">

                            <a class="col-lg-3 col-6" href="../../panel/actividad/mis_favoritos_usuario.php" style="text-decoration: none;">
                                <div class="text-center">
                                    <h1><i class="fas fa-heart text-white"></i></h1>
                                    <h4 class="text-white"><?= $totalFavoritos ?></h4>
                                    <p class="text-white">Favoritos</p>
                                </div>
                            </a><!-- box /-->

                            <a class="col-lg-3 col-6" href="../../panel/actividad/mis_likes_usuario.php" style="text-decoration: none;">
                                <div class="text-center">
                                    <h1><i class="fas fa-thumbs-up text-white"></i></h1>
                                    <h4 class="text-white"><?= $totalLikes ?></h4>
                                    <p class="text-white">Likes</p>
                                </div>
                            </a><!-- box /-->

                            <a class="col-lg-3 col-6" href="../../panel/actividad/mis_comentarios_usuario.php" style="text-decoration: none;">
                                <div class="text-center">
                                    <h1><i class="fas fa-comment text-white"></i></h1>
                                    <h4 class="text-white"><?= $totalComentarios ?></h4>
                                    <p class="text-white">Comentarios</p>
                                </div>
                            </a><!-- box /-->

                            <a class="col-lg-3 col-6" href="../../panel/actividad/mis_conversaciones_usuario.php" style="text-decoration: none;">
                                <div class="text-center">
                                    <h1><i class="fas fa-comments text-white"></i></h1>
                                    <h4 class="text-white"><?= $totalConversaciones ?></h4>
                                    <p class="text-white">Conversaciones</p>
                                </div>
                            </a><!-- box /-->
                        </div>

                    </div>

                </aside><!-- s -->

                <!--=====================================
                Nav Account
                ======================================--> 
   
                <div class="ps-section__content">

                    <ul class="ps-section__links">
                        <li class="active"><a href="../../panel/perfil/perfil_usuario.php">Mi perfil</a></li>
                        <li><a href="../../panel/perfil/editar_perfil_usuario.php">Editar perfil</a></li>
                    </ul>

                    <!--=====================================
                    Mi Perfil
                    ======================================--> 
                     <div class="ps-page__content row">

                        <div class="container">

                            <?php if ($exito): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($exito) ?></div>
                            <?php endif; ?>

                            <div class="ps-section__header">

                                <h1><?= htmlspecialchars($usuario['nombre']) ?></h1>

                                <h4>Cuenta de cliente</h4>

                            </div>

                            <div class="row mt-4">

                                <div class="col-lg-4 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5><i class="fas fa-user"></i> Nombre completo</h5>
                                            <p><?= htmlspecialchars($usuario['nombre']) ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5><i class="fas fa-envelope"></i> Correo</h5>
                                            <p><?= htmlspecialchars($usuario['email']) ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5><i class="fas fa-calendar-alt"></i> Miembro desde</h5>
                                            <p><?= htmlspecialchars(date('d/m/Y', strtotime($usuario['created_at']))) ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-4">
                                <a href="../../panel/perfil/editar_perfil_usuario.php" class="btn btn-warning btn-lg">Editar perfil</a>
                            </div>

                        </div>

                    </div>
                 

                </div>

 
            </div>

        </div>

    </div>

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