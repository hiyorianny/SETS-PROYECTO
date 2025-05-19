<?php
header('Access-Control-Allow-Origin: http://localhost:3000/');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>INICIO</title>
	<link rel="shortcut icon" href="../img/c.png" type="image/x-icon" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

	<link rel="stylesheet" href="index.css?v=<?php echo (rand()); ?>" />
</head>

<body>
	<header>
		<div class="container-hero">
			<div class="container hero">
				<div class="container-logo">
					<h1 class="logo"><a href="/"></a></h1>
				</div>
				<div class="container-logo">
					<center>
						<img src="../img/ico.png" alt="Icono de SETS" style="background-color: azure;" />
						<h1 class="logoss"><a>SETS</a></h1>
					</center>
				</div>
				<div class="container-logo">
				</div>
			</div>
		</div>
	</header>
	<header class="navbar navbar-expand-lg bg-body-tertiary shadow ">
		<div class="container d-flex justify-content-between align-items-center  a">
			<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
				class="bi bi-building-fill" viewBox="0 0 16 16">
				<path
					d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
			</svg>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<div class="navbar-nav ms-auto fs-5">
					<a class="nav-link " href="http://localhost:3000/login"><b>INICIAR SESION</b></a>
					<a class="nav-link " href="#inicio"><b>Inicio</b></a>
					<a class="nav-link" href="#servicios"><b>Servicios</b></a>
					<a class="nav-link" href="#noso"><b>Nosotros </b></a>
					<a class="nav-link" href="#contact"><b>Contáctanos</b></a>
				</div>
			</div>
		</div>
	</header>
	<center>
		<section class="banner" id="inicio">
			<div class="content-banner">

				<h2>No hay bien alguno que no nos deleite si no lo compartimos.</h2>

				<a href="http://localhost:3000/login" class="btn btn-success  sd">Iniciar sesion</a>
				<a href="http://localhost:3000/registro" class="btn btn-success sd">Registrarse</a>
			</div>
		</section>
	</center>
	<br />
	<br />
	<section class="container specials" id="servicios">
		<div class="alert alert-success z" role="alert">
			<center><strong>Servicios</strong></center>
		</div><br>
		<div class="container-products">

			<div class="card-product">
				<div class="container-img">
					<img src="../img/w.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Chat</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/coches.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Parqueadero</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/proteger.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Seguridad</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/pago.png" alt="Cafe Irish" />
				</div>
				<br />
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Pagos</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/citas.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Citas</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/campo.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Zonas comunes</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/notificaciones.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Notificaciones</b></h3>
					</div>
				</div>
			</div>
			<div class="card-product">
				<div class="container-img">
					<img src="../img/apa.png" alt="Cafe Irish" />
				</div>
				<br>
				<br>
				<div class="content-card-product">
					<div class="card-body">
						<h3 class="card-title"><b>Torres , Pisos Y Apartamentos</b></h3>
					</div>
				</div>
			</div>
	</section>
	<br>
	<br>
	<br>
	<br>
	<section class="gallery">
		<img src="../img/ñ.jpg" alt="Gallery Img1" class="gallery-img-1" /><img src="../img/o.jpg" alt="Gallery Img2"
			class="gallery-img-2" /><img src="../img/0.jpg" alt="Gallery Img3" class="gallery-img-3" /><img src="../img/9.jpg"
			alt="Gallery Img4" class="gallery-img-4" /><img src="../img/l.jpg" alt="Gallery Img5" class="gallery-img-5" />
	</section>
	<br>
	<br>
	<br>
	<br>
	<div class="alert alert-success z" role="alert">
		<center><strong>Nosotros</strong></center>
	</div>
	<div class="album py-5 bg-body-tertiary">
		<div class="container">

			<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
				<div class="col">
					<div class="alert alert-success xc" role="alert">
						<center><strong>Quienes Somos</strong></center>
					</div>
					<div class="card shadow-sm">
						<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="../img/tt.jpg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
						<title>Placeholder</title>
						<rect width="100%" height="100%" fill="#55595c" /></img>
						<div class="card-body">
							<p class="card-text bb"> <b>El software sets le permitirá organizar y mejorar la administración de una propiedad horizontal
									enfocándonos en la comunicación entre el administrador y el residentes, reducir el tiempo para
									alguna diligencia,
									donde el administrador modifica, facilitara y gestionará los sistemas de comunicación del
									residente de manera eficiente segura y transparente.</b></p>
							<div class="d-flex justify-content-between align-items-center">

								<small class="text-body-secondary">26/09/2024</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="alert alert-success xc" role="alert">
						<center><strong>Que Ofrecemos</strong></center>
					</div>
					<div class="card shadow-sm">
						<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="../img/x.jpg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
						<title>Placeholder</title>
						<rect width="100%" height="100%" fill="#55595c" /></img>
						<div class="card-body">
							<p class="card-text bb"><b>
									Soluciones integrales para mejorar la calidad de vida de los copropietarios de diversas zonas
									residenciales.
									Optimizar la comunicación entre los residentes y el personal administrativo, intentando al mismo
									tiempo garantizar
									una gestión más eficiente y adaptada. Con la implementación de esta plataforma se pretende
									promover una comunicación
									para que los ciudadanos puedan hacer uso de los recursos que ofrece el conjunto en cualquier
									momento y lugar.

								</b></p>
							<div class="d-flex justify-content-between align-items-center">

								<small class="text-body-secondary">26/09/2024</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="alert alert-success xc" role="alert">
						<center><strong>Futuro</strong></center>
					</div>
					<div class="card shadow-sm">
						<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="../img/ññ.jpg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
						<title>Placeholder</title>
						<rect width="100%" height="100%" fill="#55595c" /></img>
						<div class="card-body">
							<p class="card-text  bb"><b>
									Estamos emocionados de presentar las innovaciones que transformarán nuestro software de
									Administración de Inventarios.
									Incorporamos un Módulo de Gestión de Equipos Comunitarios, que facilita la administración de
									muebles, equipos de mantenimiento
									y suministros de limpieza, optimizando recursos y reduciendo costos. En seguridad, añadimos
									funciones avanzadas
									como reconocimiento facial, control de acceso biométrico y cámaras inteligentes, elevando la
									protección de los
									residentes y sus propiedades a un nuevo nivel.
								</b></p>
							<div class="d-flex justify-content-between align-items-center">

								<small class="text-body-secondary">26/09/2024</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<br>
	<section class="contact" id="contact">
		<div class="row">
			<div class="form-container">
				<form action="../CONTROLLER/contacto.php" method="POST">
					<h1 class="heading-12">Contáctanos</h1><br>
					<input type="text" name="nombre" placeholder="Nombre" required>
					<input type="email" name="correo" placeholder="Correo" required>
					<input type="number" name="telefono" placeholder="Teléfono" required>
					<div class="mb-3">
						<textarea class="form-control" name="comentario" rows="3" placeholder="Escribe tu comentario" required></textarea>
					</div>
					<input type="submit" class="btn btn-success" value="Enviar">
				</form>
			</div>
		</div>
	</section>

	</main>
	<footer class="footer col-12 col-6 col-2  ">
		<div class="container container-footer">
			<div class="menu-footer">
				<div class="contact-info">
					<button type="button" class="btn btn-lg btn-success title-footer" data-bs-toggle="popover" data-bs-title="info" data-bs-html="true" data-bs-content="Dirección: Av. Cra 30 No. 17-91 Sur. Bogotá - Colombia<br>
                    Teléfono: 320-251-155<br>
                    ,Email: SETS@GMAIL.com">Información de Sets</button>
					<ul>
						<li>Dirección: Av. Cra 30 No. 17-91 Sur. Bogotá - Colombia</li>
						<li>Teléfono: 320-251-155</li>
						<li>Email: SETS@GMAIL.com</li>
					</ul>

				</div>

				<div class="information ">

					<button type="button" class="btn btn-lg btn-success title-footer" data-bs-toggle="popover" data-bs-title="info" data-bs-html="true" data-bs-content="Acerca de Nosotros<br>Información<br>Políticas de Privacidad<br>Términos y condiciones<br>Contáctanos">Información</button>

					<ul>
						<li class="nav-item mb-2 nav-link p-0 text-body-secondary"><a href="#">Acerca de Nosotros</a></li>
						<li><a href="#">Información</a></li>
						<li><a href="#">Políticas de Privacidad</a></li>
						<li><a href="#">Términos y condiciones</a></li>
						<li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Contáctanos</a></li>
					</ul>

				</div>
				<div class="information">
					<button type="button" class="btn btn-lg btn-success title-footer" data-bs-toggle="popover" data-bs-title="info" data-bs-html="true"
						data-bs-content='
        <ul style="list-style: none; padding: 0; text-align: center;">
            <li style="display: inline; margin: 0 10px;">
                <span class="facebook" style="color: #3b5998;">
                    <i class="fa-brands fa-facebook-f"></i>
                </span>
            </li>
            <li style="display: inline; margin: 0 10px;">
                <span class="twitter" style="color: #1da1f2;">
                    <i class="fa-brands fa-twitter"></i>
                </span>
            </li>
            <li style="display: inline; margin: 0 10px;">
                <span class="youtube" style="color: #ff0000;">
                    <i class="fa-brands fa-youtube"></i>
                </span>
            </li>
            <li style="display: inline; margin: 0 10px;">
                <span class="whatsapp" style="color: #25D366;">
                    <i class="fa-brands fa-whatsapp"></i>
                </span>
            </li>
            <li style="display: inline; margin: 0 10px;">
                <span class="instagram" style="background-color: #f1fff9;">
                    <i class="fa-brands fa-instagram"></i>
                </span>
            </li>
        </ul>'>
						REDES SOCIALES
					</button>

					<ul>
						<ul>

							<div class="social-icons">
								<span class="facebook">
									<i class="fa-brands fa-facebook-f"></i>
								</span>
								<span class="twitter">
									<i class="fa-brands fa-twitter"></i>
								</span>
								<span class="youtube">
									<i class="fa-brands fa-youtube"></i>
								</span>
								<span class="whatsapp">
									<i class="fa-brands fa fa-whatsapp"></i>
								</span>
								<span class="instagram">
									<i class="fa-brands fa-instagram"></i>
								</span>
							</div>
						</ul>

				</div>

				<div class="newsletter">
					<p class="title-footer">Otros</p>
					<div class="content">
						<p>Si no puedes contactarnos, déjanos tu correo</p>
						<form id="newsletterForm" method="POST" action="./backend/contacto.php">
							<input type="email" name="email" id="emailInput" placeholder="Ingresa el correo aquí..." required>
							<button type="submit" class="btn btn-success">Enviar</button>
						</form>
						<div id="liveAlertPlaceholder"></div>
					</div>
				</div>


			</div>
		</div>
		<div class="extra-footer">
			<div class="col mb-3">
				<a class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
					<img class="bi me-2" width="40" height="32" src="../img/c.png">
				</a>
				<p class="text-body-secondary">&copy; 2024</p>
			</div>
			<div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
				<p>&copy; 2024 SETS, Inc. All rights reserved.</p>

			</div>
		</div>
	</footer>

	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		// Inicializar popovers
		var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
		var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
			return new bootstrap.Popover(popoverTriggerEl);
		});
	</script>

	<script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>