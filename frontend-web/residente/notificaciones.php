<?php
require '../../MODEL/backend/authMiddleware.php';
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
$decoded = authenticate();

$idRegistro = $decoded->id;
$Usuario = $decoded->Usuario;
$idRol = $decoded->idRol;


if ($idRol != 3333) {
    header("Location: http://localhost/sets/error.php");
    exit();
}

include_once "conexion.php";




$sqlAnuncios = "SELECT * FROM anuncio ";
$stmtAnuncios = $base_de_datos->prepare($sqlAnuncios);

$stmtAnuncios->execute();
$anuncios = $stmtAnuncios->fetchAll(PDO::FETCH_ASSOC);

$sqlCitas = "SELECT idcita, fechacita, horacita, tipocita FROM cita ORDER BY fechacita DESC, horacita DESC LIMIT 5";
$stmtCitas = $base_de_datos->prepare($sqlCitas);
$stmtCitas->execute();
$citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);


$sqlParqueadero = "SELECT id_solicitud , fecha_inicio, 	fecha_final,TipoVehiculo,parqueadero_visitante	 FROM solicitud_parqueadero ORDER BY fecha_inicio DESC, 	fecha_final DESC LIMIT 5";
$stmtParqueadero = $base_de_datos->prepare($sqlParqueadero);
$stmtParqueadero->execute();
$parqueaderos = $stmtParqueadero->fetchAll(PDO::FETCH_ASSOC);


$sqlZonaComun = "SELECT ID_zonaComun, fechainicio, fechafinal FROM solicitud_zona ORDER BY fechainicio DESC LIMIT 5";
$stmtZonaComun = $base_de_datos->prepare($sqlZonaComun);
$stmtZonaComun->execute();
$zonasComunes = $stmtZonaComun->fetchAll(PDO::FETCH_ASSOC);

$sqlMensajesChat = "SELECT * FROM mensajes_chat   WHERE id_destinatario = :id_usuario   ORDER BY fecha_envio DESC  LIMIT 5";
$stmtMensajesChat = $base_de_datos->prepare($sqlMensajesChat);
$stmtMensajesChat->bindParam(':id_usuario', $idRegistro);
$stmtMensajesChat->execute();
$mensajesChat = $stmtMensajesChat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Notificaciones</title>
    <link rel="stylesheet" href="css/notificaciones.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
</head>

</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
                </a><button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color: white;">
                    <span class="navbar-toggler-icon" style="color: white;"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <img src="img/C.png" alt="Logo" width="90" height="94" class="d-inline-block align-text-top">

                        <center>
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="text-align: center;">SETS</h5>
                        </center>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <center><a class="nav-link active" aria-current="page" href="#" style="font-size: 20px;"><b>Inicio</b></a></center>
                            </li>
                            <center>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <b style="font-size: 20px;"> Perfil</b>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <center><a href="Perfil.php">Editar datos</a></center>
                                        </li>
                                        <li>
                                            <center> <a href="../../MODEL/backend/logout.php">Cerrar sesión</a></center>
                                        </li>
                                    </ul>
                            </center>
                            </li>
                            <div class="offcanvas-header">
                                <img src="img/notificacion.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="notificaciones.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;">Notificaciones</a>
                                </center>
                            </div>

                        </ul>
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br><br><br> <br>
    <section id="chatContainer" class="chat-container position-fixed p-5 rounded-3" style="z-index: 1000; bottom: 20px; right: 20px;">
        <div class="chat-header">
            <span id="chatHeader">Chat</span>
            <button class="close-btn" onclick="closeChat()">×</button>
        </div>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
            <input type="text" id="chatInput" placeholder="Escribe tu Mensaje...">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </section>
    </main>
    <br>
    <br>

    <div class="container">
        <center>
            <div class="alert alert-success" role="alert" style="font-size: 34px;">
                <b> NOTIFICACIONES</b>
            </div>
        </center>
        <div class="email-list">
            <?php foreach ($citas as $cita): ?>
                <div class="email-item" data-id="cita_<?php echo $cita['idcita']; ?>">
                    <div class="email-sender">Cita: <?php echo htmlspecialchars($cita['tipocita']); ?></div>
                    <div class="email-subject">Fecha: <?php echo htmlspecialchars($cita['fechacita']); ?> - Hora: <?php echo htmlspecialchars($cita['horacita']); ?></div>
                    <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                    <a href="./citasFormulario.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR</center>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php foreach ($anuncios as $anuncio): ?>
                <div class="email-item" data-id="anuncio_<?php echo $anuncio['idAnuncio']; ?>">
                    <div class="email-sender">Anuncio: <?php echo htmlspecialchars($anuncio['titulo']); ?></div>
                    <div class="email-subject">Publicado el: <?php echo htmlspecialchars($anuncio['fechaPublicacion']); ?></div>
                    <div class="email-snippet">Descripción: <?php echo htmlspecialchars($anuncio['descripcion']); ?></div>
                    <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                    <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR</center>
                    </a>
                </div>
            <?php endforeach; ?>

            <?php foreach ($parqueaderos as $parqueadero): ?>
                <div class="email-item" data-id="parqueadero_<?php echo $parqueadero['id_solicitud']; ?>">
                    <b>Solicitud de Parqueadero</b><br>
                    <b>Tipo Vehiculo:</b> <?php echo htmlspecialchars($parqueadero['TipoVehiculo']); ?><br>
                    <b>Fecha Inicio:</b> <?php echo htmlspecialchars($parqueadero['fecha_inicio']); ?><br>
                    <b>Parqueadero:</b> <?php echo htmlspecialchars($parqueadero['parqueadero_visitante']); ?><br>
                    <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                    <a href="./parqueaderocarro.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR CARRO</center>
                    </a>
                    <a href="./paromoto.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR MOTO</center>
                    </a>
                </div>
            <?php endforeach; ?>


            <?php foreach ($mensajesChat as $mensaje): ?>
                <div class="email-item" data-id="<?php echo $mensaje['id_mensaje']; ?>">
                    <b>Nuevo Mensaje</b><br>
                    <b>De:</b> <?php echo htmlspecialchars($mensaje['id_remitente']); ?><br>
                    <b>Fecha:</b> <?php echo htmlspecialchars($mensaje['fecha_envio']); ?><br>
                    <b>Mensaje:</b> <?php echo htmlspecialchars($mensaje['contenido']); ?><br>
                    <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                    <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR AL CHAT</center>
                    </a>
                </div>
                <br>
            <?php endforeach; ?>

            <?php foreach ($zonasComunes as $zonaComun): ?>
                <div class="email-item" data-id="zona_<?php echo $zonaComun['ID_zonaComun']; ?>">
                    <b>Solicitud de Zona Común</b><br>
                    <b>ID_zonaComun:</b> <?php echo htmlspecialchars($zonaComun['ID_zonaComun']); ?><br>
                    <b>Inicio:</b> <?php echo htmlspecialchars($zonaComun['fechainicio']); ?><br>
                    <b>Final:</b> <?php echo htmlspecialchars($zonaComun['fechafinal']); ?><br>
                    <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                    <a href="./zonas_comunes.php" class="btn btn-outline-success" style="font-size:15px;">
                        <center>IR</center>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let usuario = "<?php echo htmlspecialchars($Usuario); ?>";


            let hiddenNotifications = JSON.parse(localStorage.getItem("hiddenNotifications_" + usuario)) || [];

            // Ocultar notificaciones descartadas
            document.querySelectorAll(".email-item").forEach(item => {
                let notifId = item.getAttribute("data-id");
                if (hiddenNotifications.includes(notifId)) {
                    item.style.display = "none"; // Ocultar
                }
            });

            document.querySelectorAll(".remove-notif").forEach(button => {
                button.addEventListener("click", function() {
                    let parent = this.parentElement;
                    let notifId = parent.getAttribute("data-id");

                    // Agregar la notificación
                    if (!hiddenNotifications.includes(notifId)) {
                        hiddenNotifications.push(notifId);
                    }

                    // Guardar en localStorage con el nombre del usuario
                    localStorage.setItem("hiddenNotifications_" + usuario, JSON.stringify(hiddenNotifications));


                    parent.style.display = "none";
                });
            });
        });
    </script>
    <script>
        function checkNewMessages() {
            fetch('./chat/check_messages.php?id_usuario=<?php echo $idRegistro; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.newMessages) {

                        alert('Tienes nuevos mensajes!');

                        location.reload();
                    }
                });
        }
        setInterval(checkNewMessages, 30000);
    </script>

    </main>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size:30px;   background-color: #0e2c0a;">
            <center>VOLVER</center>
        </a>
    </div>

    <script>
        function toggleExpand(element) {
            element.classList.toggle('expanded');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>