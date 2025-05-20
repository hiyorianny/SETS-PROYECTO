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



// Verificar si se ha proporcionado el ID de la solicitud
if (isset($_GET['ID_Apartamentooss'])) {
    $idSolicitud = $_GET['ID_Apartamentooss'];
    // Consulta para obtener los datos de la solicitud
    $query = "SELECT * FROM solicitud_zona WHERE ID_Apartamentooss = :ID_Apartamentooss";
    $statement = $base_de_datos->prepare($query);
    $statement->bindParam(':ID_Apartamentooss', $idSolicitud);
    $statement->execute();
    $solicitud = $statement->fetch(PDO::FETCH_ASSOC);
    // Verificar si la solicitud existe
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
    <title>SETS - Actualizar GYM</title>
    <link rel="stylesheet" href="css/gym.css?v=<?php echo (rand()); ?>">
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

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
                </a> <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color: white;">
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
            <div class="chat-messages" id="chatMessages">
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </section>
    </main>
    <br><br><br>
    <div class="alert alert-success" role="alert">
        <h2 style="text-align: center;">Actualizar Agendación Del Gimnasio </h2>
        <p>
    </div>
    <br>
    <div class="container">
        <section class="login-content">
            <div class="container">
                <form action="../../CONTROLLER/gym.php" method="POST">
                    <img src="img/gym-equipment.png" alt="Logo" class="imgp">
                    <input type="hidden" name="idSolicitud" value="<?= htmlspecialchars($solicitud['ID_Apartamentooss']) ?>">
                    <div class="form-group">
                        <label for="fechainicio">Fecha de Inicio:</label>
                        <input type="date" name="fechainicio" value="<?= htmlspecialchars($solicitud['fechainicio']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Hora_inicio">Hora de Inicio:</label>
                        <input type="time" name="Hora_inicio" value="<?= htmlspecialchars($solicitud['Hora_inicio']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fechafinal">Fecha de Finalización:</label>
                        <input type="date" name="fechafinal" value="<?= htmlspecialchars($solicitud['fechafinal']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Hora_final">Hora de Finalización:</label>
                        <input type="time" name="Hora_final" value="<?= htmlspecialchars($solicitud['Hora_final']) ?>" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </form>
                <br>
        </section>
    </div>
    <br>
    <br>
    <br>
    <br>
    <a href="solicitargym.php" class="btn btn-danger btn-lg">volver</a>
    <script type="text/javascript" src="JAVA/main.js"></script>
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
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const fechaInicioInput = document.querySelector('input[name="fechainicio"]');
            const fechaFinalInput = document.querySelector('input[name="fechafinal"]');
            const horaInicioInput = document.querySelector('input[name="Hora_inicio"]');
            const horaFinalInput = document.querySelector('input[name="Hora_final"]');

            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const fechaHoy = yyyy + '-' + mm + '-' + dd;

            fechaInicioInput.setAttribute('min', fechaHoy);
            fechaFinalInput.setAttribute('min', fechaHoy);


            fechaInicioInput.addEventListener('change', function() {
                const fechaInicio = new Date(this.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                if (fechaInicio < hoy) {
                    alert('No puedes seleccionar una fecha en el pasado');
                    this.value = fechaHoy;
                    return;
                }


                fechaFinalInput.min = this.value;


                if (new Date(fechaFinalInput.value) < fechaInicio) {
                    fechaFinalInput.value = this.value;
                }

                if (fechaInicioInput.value === fechaFinalInput.value) {
                    validarHorasMismoDia();
                }
            });


            fechaFinalInput.addEventListener('change', function() {
                const fechaInicio = new Date(fechaInicioInput.value);
                const fechaFinal = new Date(this.value);

                if (fechaFinal < fechaInicio) {
                    alert('La fecha final no puede ser anterior a la fecha de inicio');
                    this.value = fechaInicioInput.value;
                    return;
                }


                if (fechaInicioInput.value === fechaFinalInput.value) {
                    validarHorasMismoDia();
                }
            });


            horaInicioInput.addEventListener('change', validarHorasMismoDia);
            horaFinalInput.addEventListener('change', validarHorasMismoDia);

            function validarHorasMismoDia() {
                if (fechaInicioInput.value === fechaFinalInput.value) {
                    if (horaInicioInput.value && horaFinalInput.value) {
                        if (horaInicioInput.value >= horaFinalInput.value) {
                            alert('La hora de inicio no puede ser posterior o igual a la hora final en el mismo día');
                            horaInicioInput.value = '';
                            horaFinalInput.value = '';
                        }
                    }
                }
            }


            form.addEventListener('submit', function(e) {
                const fechaInicio = new Date(fechaInicioInput.value);
                const fechaFinal = new Date(fechaFinalInput.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                // Validar fechas
                if (fechaInicio < hoy) {
                    alert('No puedes actualizar la solicitud con fecha en el pasado');
                    e.preventDefault();
                    return false;
                }

                if (fechaFinal < fechaInicio) {
                    alert('La fecha final no puede ser anterior a la fecha de inicio');
                    e.preventDefault();
                    return false;
                }


                if (fechaInicioInput.value === fechaFinalInput.value) {
                    if (!horaInicioInput.value || !horaFinalInput.value) {
                        alert('Debes especificar ambas horas cuando es el mismo día');
                        e.preventDefault();
                        return false;
                    }

                    if (horaInicioInput.value >= horaFinalInput.value) {
                        alert('La hora de inicio no puede ser posterior o igual a la hora final');
                        e.preventDefault();
                        return false;
                    }
                }


                if (fechaInicioInput.value === fechaFinalInput.value) {
                    const [horaIni, minIni] = horaInicioInput.value.split(':').map(Number);
                    const [horaFin, minFin] = horaFinalInput.value.split(':').map(Number);

                    const diffHoras = horaFin - horaIni;
                    const diffMinutos = minFin - minIni;
                    const totalMinutos = diffHoras * 60 + diffMinutos;

                    if (totalMinutos < 60) {
                        alert('La reserva debe tener al menos 1 hora de duración');
                        e.preventDefault();
                        return false;
                    }
                }

                return true;
            });


            if (fechaInicioInput.value === fechaFinalInput.value) {
                validarHorasMismoDia();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>