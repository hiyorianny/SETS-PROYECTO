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


if ($idRol != 1111) {
    header("Location: http://localhost/sets/error.php");
    exit();
}

include_once "conexion.php";



$sql = "SELECT * FROM solicitud_parqueadero WHERE tipoVehiculo = 'moto'";

$stmt = $base_de_datos->query($sql);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$sql_estado = "SELECT 
    p.parqueadero_visitante AS parqueadero,
    CASE 
        WHEN p.estado = 'aprobado' AND p.tipoVehiculo = 'moto' AND NOW() BETWEEN p.fecha_inicio AND p.fecha_final THEN 'ocupado'
        WHEN p.estado = 'aprobado' AND p.tipoVehiculo = 'moto' AND NOW() < p.fecha_inicio THEN 'reservado'
        ELSE 'disponible'
    END AS estado,
    IFNULL(p.nombre_visitante, '') AS visitante,
    IFNULL(p.placaVehiculo, '') AS placa,
    IFNULL(p.tipoVehiculo, '') AS tipo_vehiculo,
    IFNULL(CONCAT(DATE_FORMAT(p.fecha_inicio, '%d/%m/%Y %H:%i'), ' - ', DATE_FORMAT(p.fecha_final, '%d/%m/%Y %H:%i')), '') AS horario
FROM 
    (SELECT 'V1' AS parqueadero_visitante UNION SELECT 'V2' UNION SELECT 'V3' UNION 
     SELECT 'V4' UNION SELECT 'V5' UNION SELECT 'V6' UNION 
     SELECT 'V7' UNION SELECT 'V8' UNION SELECT 'V9' UNION SELECT 'V10') AS todos_parqueaderos
LEFT JOIN solicitud_parqueadero p ON 
    todos_parqueaderos.parqueadero_visitante = p.parqueadero_visitante AND
    p.estado = 'aprobado' AND
    p.tipoVehiculo = 'moto' AND
    NOW() <= p.fecha_final
GROUP BY 
    todos_parqueaderos.parqueadero_visitante
ORDER BY parqueadero";


$stmt_estado = $base_de_datos->query($sql_estado);
$estado_parqueaderos = $stmt_estado->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - MOTO</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/citas.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
            <img src="img/ajustes.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
            <b style="font-size: 25px;color:aliceblue"> ADMIN - <?php echo htmlspecialchars($Usuario); ?>  </b></a>
             <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color: white;">
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
                                            <center><a href="Perfil.php">Editar Datos</a></center>
                                        </li>
                                        <li>
                                            <center> <a href="../../MODEL/backend/logout.php">Cerrar Sesión</a></center>
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
                            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <main>
        <section class="chat-container" id="chatContainer">
            <header class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </header>
            <div class="chat-messages" id="chatMessages">
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </section>
    </main>
    <main>


        <div class="container">
        <div class="alert alert-success" role="alert" style="text-align: center; font-size: 24px;">
                <b>Estado de Parqueaderos para Motos Visitantes</b>
            </div>

            <div class="row mb-5">
                <?php foreach ($estado_parqueaderos as $parqueadero): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card 
                <?php
                    if ($parqueadero['estado'] == 'ocupado') echo 'bg-danger text-white';
                    elseif ($parqueadero['estado'] == 'reservado') echo 'bg-warning';
                    else echo 'bg-success text-white';
                ?>">
                            <div class="card-header">
                                <h5 class="card-title">Parqueadero Moto <?= htmlspecialchars($parqueadero['parqueadero']) ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Estado:</strong> <?= ucfirst(htmlspecialchars($parqueadero['estado'])) ?><br>
                                    <?php if ($parqueadero['estado'] != 'disponible'): ?>
                                        <strong>Visitante:</strong> <?= htmlspecialchars($parqueadero['visitante']) ?><br>
                                        <strong>Placa:</strong> <?= htmlspecialchars($parqueadero['placa']) ?><br>
                                        <strong>Tipo:</strong> Moto<br>
                                        <strong>Horario:</strong> <?= htmlspecialchars($parqueadero['horario']) ?>
                                    <?php else: ?>
                                        <strong>Disponible para motos</strong>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>




            <div class="sidebar">

                <br>
                <div class="barra">
                    <div class="sombra"></div>
                    <input type="text" placeholder="Buscar moto...">
                    <ion-icon name="search-outline"></ion-icon>
                </div>
                <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
                <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
                <br>
                <div class="appointment-list">
                    <center>

                    </center>
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <div class="appointment">
                            <center>
                                <div class="alert alert-success" role="alert">
                                    <h3><b>Solicitud de Agendacion</b></h3>
                                </div>

                            </center>
                            <center>
                            <p><strong>id de la solicitud :</strong> <?= htmlspecialchars($solicitud['id_solicitud']) ?></p>
                                <p><strong>Numero de Apartamento:</strong> <?= htmlspecialchars($solicitud['id_apartamento']) ?></p>
                                <p><strong>Fecha de Inicio:</strong> <?= date('d/m/Y', strtotime($solicitud['fecha_inicio'])) ?></p>
                                <p><strong>Fecha Final:</strong> <?= date('d/m/Y', strtotime($solicitud['fecha_final'])) ?></p>
                                <p><strong>Numero del Parqueadero:</strong> <?= htmlspecialchars($solicitud['parqueadero_visitante']) ?></p>
                                <p><strong>Placa del Vehículo:</strong> <?= htmlspecialchars($solicitud['placaVehiculo']) ?></p>
                                <p><strong>Color del Vehículo:</strong> <?= htmlspecialchars($solicitud['colorVehiculo']) ?></p>
                                <p><strong>Tipo de Vehículo:</strong> <?= htmlspecialchars($solicitud['tipoVehiculo']) ?></p>
                                <p><strong>Nombre del Dueño:</strong> <?= htmlspecialchars($solicitud['nombre_visitante']) ?></p>
                                <p><strong>Modelo del Vehículo:</strong> <?= htmlspecialchars($solicitud['modelo']) ?></p>
                                <p><strong>Marca del Vehículo:</strong> <?= htmlspecialchars($solicitud['marca']) ?></p>
                                <p><strong>SOLICITUD FUE:</strong> <?= $solicitud['estado'] ?> </p>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <!-- Formulario para aceptar la solicitud -->
                                    <form action="../../CONTROLLER/mot.php" method="POST">
                                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>"> <!-- o ID_zonaComun -->
                                        <input type="hidden" name="accion" value="aprobado"> <!-- Antes: "aceptar" -->
                                        <button type="submit" class="btn btn-success"><b>Aceptar</b></button>
                                    </form>

                                    <!-- Formulario para dejar la solicitud como pendiente -->
                                    <form action="../../CONTROLLER/mot.php" method="POST">
                                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>"> <!-- o ID_zonaComun -->
                                        <input type="hidden" name="accion" value="pendiente">
                                        <button type="submit" class="btn btn-warning"><b>Pendiente</b></button>
                                    </form>

                                    <!-- Formulario para eliminar la solicitud -->
                                    <form action="../../CONTROLLER/mot.php" method="POST">
                                        <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>"> <!-- o ID_zonaComun -->
                                        <input type="hidden" name="accion" value="rechazado"> <!-- Antes: "eliminar" -->
                                        <button type="submit" class="btn btn-danger"><b>Eliminar</b></button>
                                    </form>

                                </div>
                            </center>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
    </main>
    <a href="paromoto.php" class="btn btn-outline-success" style="font-size: 30px;">
        <center>VOLVER</center>
    </a>
    <script>
        document.querySelector('.admin-img').addEventListener('click', function() {
            document.querySelector('.dropdown-menu').classList.toggle('show');
        });

        document.querySelector('.chat-button').addEventListener('click', function() {
            document.querySelector('.chat-menu').classList.toggle('show');
        });

        function filterChat() {
            const searchInput = document.querySelector('.search-bar').value.toLowerCase();
            const chatItems = document.querySelectorAll('.chat-item');
            chatItems.forEach(item => {
                if (item.textContent.toLowerCase().includes(searchInput)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <script>
        function openChat(chatName) {
            const chatContainer = document.getElementById('chatContainer');
            const chatHeader = document.getElementById('chatHeader');
            chatHeader.textContent = chatName;
            chatContainer.classList.add('show');
        }

        function closeChat() {
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.classList.remove('show');
        }

        function sendMessage() {
            const messageInput = document.getElementById('chatInput');
            const messageText = messageInput.value.trim();
            if (messageText) {
                const chatMessages = document.getElementById('chatMessages');
                const messageElement = document.createElement('p');
                messageElement.textContent = messageText;
                chatMessages.appendChild(messageElement);
                messageInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        function filterChat() {
            const searchInput = document.querySelector('.search-bar').value.toLowerCase();
            const chatItems = document.querySelectorAll('.chat-item');
            chatItems.forEach(item => {
                if (item.textContent.toLowerCase().includes(searchInput)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    </main>
</body>
<br>
    <br>
    <br>
    <footer> 
  <div class="footer-content">
    <li >&copy; 2025 SETS. Todos los derechos reservados.</li>
    <ul>
      <li><a href="#">Términos y Condiciones</a></li>
      <li><a href="#">Política de Privacidad</a></li>
      <li><a href="#">Contacto</a></li>
    </ul>
  </div>
</footer>
</html>