<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000");  
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");  

if (!isset($_SESSION['Usuario'])) {
    header("Location: http://localhost/sets/login.php");
    exit();
}


if ($_SESSION['idRol'] != 4) { // Solo si el rol es "residente" (idRol == 4)
    header("Location: http://localhost/sets/error.php");
    exit();
}



include_once "conexion.php";



if (isset($_GET['id_parking'])) {
    $idSolicitud = $_GET['id_parking'];

    $query = "SELECT * FROM solicitud_parqueadero WHERE id_parking = :id_parking";
    $statement = $base_de_datos->prepare($query);
    $statement->bindParam(':id_parking', $idSolicitud);
    $statement->execute();
    $solicitud = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$solicitud) {
        echo "Solicitud no encontrada.";
        exit();
    }
} else {
    echo "ID de solicitud no proporcionado";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Actualizar PCarro</title>
    <link rel="stylesheet" href="css/caaaa.css?v=<?php echo (rand()); ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
                <b style="font-size: 30px;color:aliceblue"> Residente </b>
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
                                            <center><a href="Perfil.php">Editar datos</a></center>
                                        </li>
                                        <li>
                                            <center><a href="#">Reportar problema</a></center>
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
                            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br><br>
    <main>
        <section class="chat-container" id="chatContainer">
            <header class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </header>
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </section>
    </main>
    <br><br>
    <div class="alert alert-success" role="alert">
        <h2 style="text-align: center;font-size: 20px;"><b>Editar Solicitud de Agendación Parqueadero !</b></h2>
        <p><br></p>
    </div>
    <div class="container">
        <section class="login-content">
            <img src="img/esta.png" alt="Logo" class="imgp">
            <div class="form-container">
                <form action="servidor-parqueaderos/carr.php" method="POST">
                    <input type="hidden" name="id_parking" value="<?php echo $solicitud['id_parking']; ?>">

                    <div class="form-group">
                        <label for="fechaInicio">Número de Apartamento:</label>
                        <input type="number" name="id_Aparta" value="<?= htmlspecialchars($solicitud['id_Aparta']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($solicitud['fecha_inicio']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="hora_inicio">Hora de Inicio:</label>
                        <input type="time" name="hora_inicio" value="<?= htmlspecialchars($solicitud['hora_inicio']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha_final">Fecha de Finalización:</label>
                        <input type="date" name="fecha_final" value="<?= htmlspecialchars($solicitud['fecha_final']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="hora_final">Hora de Finalización:</label>
                        <input type="time" name="hora_final" value="<?= htmlspecialchars($solicitud['hora_final']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="numParqueadero">Número de Parqueadero:</label>
                        <input type="number" name="numParqueadero" value="<?= htmlspecialchars($solicitud['numParqueadero']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="placaVehiculo">Placa del Vehículo:</label>
                        <input type="text" name="placaVehiculo" value="<?= htmlspecialchars($solicitud['placaVehiculo']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="colorVehiculo">Color del Vehículo:</label>
                        <input type="text" name="colorVehiculo" value="<?= htmlspecialchars($solicitud['colorVehiculo']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="TipoVehiculo">Tipo de Vehículo:</label>
                        <input type="text" name="TipoVehiculo" value="<?= htmlspecialchars($solicitud['TipoVehiculo']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" name="modelo" value="<?= htmlspecialchars($solicitud['modelo']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" name="marca" value="<?= htmlspecialchars($solicitud['marca']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="descripcionvehiculo">Descripción del Vehículo:</label>
                        <input type="text" name="descripcionvehiculo" value="<?= htmlspecialchars($solicitud['descripcionvehiculo']) ?>" required class="form-control">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success"><b>Actualizar Solicitud</b></button>
                </form>
            </div>
        </section>
    </div>

    <a href="horariocarro.php" class="btn btn-danger btn-lg">Volver</a>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        function openChat(user) {
            document.getElementById('chatHeader').innerText = user;
            document.querySelector('.chat-container').classList.add('show');
        }

        function closeChat() {
            document.querySelector('.chat-container').classList.remove('show');
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value;
            if (message) {
                const messagesDiv = document.getElementById('chatMessages');
                messagesDiv.innerHTML += `<div>${message}</div>`;
                input.value = '';
            }
        }
    </script>
</body>

</html>