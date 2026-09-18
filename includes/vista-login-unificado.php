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

	<title>RedNegocios | Iniciar sesión</title>

	<link rel="icon" href="assets/img/template/icono.png">

	<!--=====================================
	CSS
	======================================-->

	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&display=swap" rel="stylesheet">

	<!-- font awesome -->
	<link rel="stylesheet" href="assets/css/plugins/fontawesome.min.css">

	<!-- linear icons -->
	<link rel="stylesheet" href="assets/css/plugins/linearIcons.css">

	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<!-- Owl Carousel -->
	<link rel="stylesheet" href="assets/css/plugins/owl.carousel.css">

	<!-- Slick -->
	<link rel="stylesheet" href="assets/css/plugins/slick.css">

	<!-- Light Gallery -->
	<link rel="stylesheet" href="assets/css/plugins/lightgallery.min.css">

	<!-- Font Awesome Start -->
	<link rel="stylesheet" href="assets/css/plugins/fontawesome-stars.css">

	<!-- jquery Ui -->
	<link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">

	<!-- Select 2 -->
	<link rel="stylesheet" href="assets/css/plugins/select2.min.css">

	<!-- Scroll Up -->
	<link rel="stylesheet" href="assets/css/plugins/scrollUp.css">

	<!-- estilo principal -->
	<link rel="stylesheet" href="assets/css/style.css">

	<!-- Market Place 4 -->
	<link rel="stylesheet" href="assets/css/market-place-4.css">

	<!--=====================================
	PLUGINS JS
	======================================-->

	<!-- jQuery library -->
	<script src="assets/js/plugins/jquery-1.12.4.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<!-- Owl Carousel -->
	<script src="assets/js/plugins/owl.carousel.min.js"></script>

	<!-- Images Loaded -->
	<script src="assets/js/plugins/imagesloaded.pkgd.min.js"></script>

	<!-- Masonry -->
	<script src="assets/js/plugins/masonry.pkgd.min.js"></script>

	<!-- Isotope -->
	<script src="assets/js/plugins/isotope.pkgd.min.js"></script>

	<!-- jQuery Match Height -->
	<script src="assets/js/plugins/jquery.matchHeight-min.js"></script>

	<!-- Slick -->
	<script src="assets/js/plugins/slick.min.js"></script>

	<!-- jQuery Barrating -->
	<script src="assets/js/plugins/jquery.barrating.min.js"></script>

	<!-- Slick Animation -->
	<script src="assets/js/plugins/slick-animation.min.js"></script>

	<!-- Light Gallery -->
	<script src="assets/js/plugins/lightgallery-all.min.js"></script>

	<!-- jQuery UI -->
	<script src="assets/js/plugins/jquery-ui.min.js"></script>

	<!-- Sticky Sidebar -->
	<script src="assets/js/plugins/sticky-sidebar.min.js"></script>

	<!-- Slim Scroll -->
	<script src="assets/js/plugins/jquery.slimscroll.min.js"></script>

	<!-- Select 2 -->
	<script src="assets/js/plugins/select2.full.min.js"></script>

	<!-- Scroll Up -->
	<script src="assets/js/plugins/scrollUP.js"></script>

</head>

<body>


	<!--=====================================
	Header Promotion
	======================================-->

	<div class="ps-block--promotion-header bg--cover" style="background: url(assets/img/banner/top/header-promotion.jpg);">
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

        <div class="header__top">

            <div class="container">

                <div class="header__left">
                    <ul class="d-flex justify-content-center">
						<li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
						<li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>
						<li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
						<li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
					</ul>
                </div>

                <div class="header__right">
                    <ul class="header__top-links">
                    	<li><i class="icon-telephone"></i> Contacto:<strong> 1-800-234-5678</strong></li>
                        <li>
                            <div class="ps-dropdown language"><a href="#"><img src="assets/img/template/es.png" alt="">Español</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div><!-- End Container -->

        </div><!-- Header Top -->

        <div class="header__content">

            <div class="container">

                <div class="header__content-left">

                	<a class="ps-logo" href="index.php">
                		<img src="assets/img/template/logo_light.png" alt="">
                	</a>

                </div><!-- End Header Content Left-->

                <div class="header__content-right">

                    <div class="header__actions">

                        <div class="ps-block--user-header">
                            <div class="ps-block__left">
                            	<i class="icon-user"></i>
                            </div>
                            <div class="ps-block__right">
                            	<?php if (proveedorAutenticado()): ?>
                            	<a href="panel/perfil/perfil_negocio.php">Mi cuenta</a>
                            	<a href="logout_negocio.php">Cerrar sesión</a>
                            	<?php elseif (clienteAutenticado()): ?>
                            	<a href="panel/perfil/perfil_usuario.php">Mi cuenta</a>
                            	<a href="usuario/logout_usuario.php">Cerrar sesión</a>
                            	<?php else: ?>
                            	<a href="login.php#sign-in">Iniciar sesión</a>
                            	<?php endif; ?>
                            </div>
                        </div>

                    </div><!-- End Header Actions-->

                </div><!-- End Header Content Right-->

            </div><!-- End Container-->

        </div><!-- End Header Content-->

    </header>

    <!--=====================================
    Breadcrumb
    ======================================-->

	<div class="ps-breadcrumb">

        <div class="container">

            <ul class="breadcrumb">

                <li><a href="index.php">Inicio</a></li>

                <li>Iniciar sesión</li>

            </ul>

        </div>

    </div>

    <!--=====================================
    Login Content
    ======================================-->

    <div class="ps-my-account">

        <div class="container">

            <div class="ps-form--account ps-tab-root">

                <ul class="ps-tab-list">

                    <li class="<?= $tabActiva === 'sign-in' ? 'active' : '' ?>"><a href="#sign-in">Iniciar sesión</a></li>

                    <li class="<?= $tabActiva === 'register' ? 'active' : '' ?>"><a href="#register">Registrarse</a></li>

                </ul>

                <div class="ps-tabs">

                    <!--=====================================
                    Login Form
                    ======================================-->

                    <div class="ps-tab <?= $tabActiva === 'sign-in' ? 'active' : '' ?>" id="sign-in">

                        <form action="login_procesar.php" method="POST">

                        <div class="ps-form__content">

                            <h5>Inicia sesión en tu cuenta</h5>

                            <?php if (!empty($erroresLogin)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($erroresLogin as $error): ?>
                                <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">

                                <input class="form-control" type="email" name="email" placeholder="Correo electrónico" required>

                            </div>

                            <div class="form-group form-forgot">

                                <input class="form-control" type="password" name="password" placeholder="Contraseña" required>

                                <a href="">¿Olvidaste tu contraseña?</a>

                            </div>

                            <div class="form-group">

                                <div class="ps-checkbox">

                                    <input class="form-control" type="checkbox" id="remember-me" name="remember-me">

                                    <label for="remember-me">Recordarme</label>

                                </div>

                            </div>

                            <div class="form-group submtit">

                                <button type="submit" class="ps-btn ps-btn--fullwidth">Iniciar sesión</button>

                            </div>

                        </div>

                        </form>

                        <div class="ps-form__footer">

                            <p>Conéctate con:</p>

                            <ul class="ps-list--social">

                                <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a class="google" href="#"><i class="fab fa-google"></i></a></li>

                            </ul>

                        </div>

                    </div><!-- End Login Form -->

                    <!--=====================================
                    Register Form
                    ======================================-->

                    <div class="ps-tab <?= $tabActiva === 'register' ? 'active' : '' ?>" id="register">

                        <form action="registro_procesar.php" method="POST" id="form-registro">

                        <div class="ps-form__content">

                            <h5>Crea tu cuenta</h5>

                            <?php if (!empty($erroresRegistro)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($erroresRegistro as $error): ?>
                                <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">

                                <label class="d-block">¿Qué tipo de cuenta quieres crear?</label>

                                <select class="form-control" name="tipo_cuenta" id="tipo_cuenta">
                                    <option value="cliente" <?= $tipoRegistro === 'cliente' ? 'selected' : '' ?>>Soy cliente (quiero comprar)</option>
                                    <option value="negocio" <?= $tipoRegistro === 'negocio' ? 'selected' : '' ?>>Tengo un negocio (quiero vender)</option>
                                </select>

                            </div>

                            <div class="form-group">

                                <input class="form-control" type="text" name="nombre" placeholder="Nombre completo" required>

                            </div>

                            <div class="form-group">

                                <input class="form-control" type="email" name="email" placeholder="Correo electrónico" required>

                            </div>

                            <div class="form-group">

                                <input class="form-control" type="password" name="password" placeholder="Contraseña" minlength="8" required>

                            </div>

                            <div class="form-group">

                                <input class="form-control" type="password" name="password_confirm" placeholder="Confirmar contraseña" minlength="8" required>

                            </div>

                            <div id="campos-negocio" style="display: <?= $tipoRegistro === 'negocio' ? 'block' : 'none' ?>;">

                                <div class="form-group">

                                    <input class="form-control campo-negocio" type="text" name="nombre_negocio" placeholder="Nombre del negocio">

                                </div>

                                <div class="form-group">

                                    <select class="form-control campo-negocio" name="categoria_id">
                                        <option value="" selected disabled>Categoría / Giro del negocio</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                                <div class="form-group">

                                    <input class="form-control campo-negocio" type="text" name="direccion" placeholder="Dirección">

                                </div>

                                <div class="form-group">

                                    <input class="form-control campo-negocio" type="text" name="telefono" placeholder="Teléfono">

                                </div>

                                <div class="form-group">

                                    <input class="form-control" type="text" name="whatsapp" placeholder="WhatsApp (opcional)">

                                </div>

                            </div>

                            <div class="form-group submtit">

                                <button type="submit" class="ps-btn ps-btn--fullwidth">Registrarme</button>

                            </div>

                        </div>

                        </form>

                        <div class="ps-form__footer">

                            <p>Conéctate con:</p>

                            <ul class="ps-list--social">

                                <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a class="google" href="#"><i class="fab fa-google"></i></a></li>

                            </ul>

                        </div>

                    </div><!-- End Register Form -->

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

                <aside class="widget widget_footer">

                    <h4 class="widget-title">Enlaces rápidos</h4>

                    <ul class="ps-list--link">

                        <li><a href="#">Política de privacidad</a></li>

                        <li><a href="#">Términos y condiciones</a></li>

                        <li><a href="faqs.html">Preguntas frecuentes</a></li>

                    </ul>

                </aside>

                <aside class="widget widget_footer">

                    <h4 class="widget-title">Empresa</h4>

                    <ul class="ps-list--link">

                        <li><a href="about-us.html">Sobre nosotros</a></li>

                        <li><a href="contact-us.html">Contacto</a></li>

                    </ul>

                </aside>

            </div>

            <div class="ps-footer__copyright">

                <p>© 2026 RedNegocios. Todos los derechos reservados</p>

            </div>

        </div>

    </footer>

	<!--=====================================
	JS PERSONALIZADO
	======================================-->

	<script src="assets/js/main.js"></script>

	<script>
	(function () {
		// Si llegamos aqui con #register o #sign-in desde OTRA pagina (ej. index.php),
		// el navegador nunca envia ese fragmento al servidor, asi que PHP no puede
		// saber cual pestaña mostrar. Lo corregimos aqui mismo al cargar la pagina.
		var hash = window.location.hash;
		if (hash === '#register' || hash === '#sign-in') {
			var targetId = hash.substring(1);
			document.querySelectorAll('.ps-tab-list li').forEach(function (li) {
				li.classList.remove('active');
			});
			document.querySelectorAll('.ps-tab').forEach(function (pane) {
				pane.classList.remove('active');
			});
			var targetPane = document.getElementById(targetId);
			if (targetPane) { targetPane.classList.add('active'); }
			var targetLink = document.querySelector('.ps-tab-list a[href$="' + hash + '"]');
			if (targetLink && targetLink.closest('li')) { targetLink.closest('li').classList.add('active'); }
		}
	})();

	(function () {
		var select = document.getElementById('tipo_cuenta');
		var camposNegocio = document.getElementById('campos-negocio');
		if (!select || !camposNegocio) { return; }

		var camposObligatorios = camposNegocio.querySelectorAll('.campo-negocio');

		function actualizar() {
			var esNegocio = select.value === 'negocio';
			camposNegocio.style.display = esNegocio ? 'block' : 'none';
			camposObligatorios.forEach(function (campo) {
				if (esNegocio) {
					campo.setAttribute('required', 'required');
				} else {
					campo.removeAttribute('required');
				}
			});
		}

		select.addEventListener('change', actualizar);
		actualizar();
	})();
	</script>

</body>
</html>
