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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $idcita = $_POST['delete_idcita'];

    // Borrar una cita
    $sql = "DELETE FROM cita WHERE idcita = :idcita";
    $stmt = $base_de_datos->prepare($sql);

    if ($stmt->execute(['idcita' => $idcita])) {
    } else {
        echo "Error al eliminar la cita.";
    }
}
$sql = "SELECT * FROM cita";
$stmt = $base_de_datos->query($sql);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Reservar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
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
    <main>
        <br> <br> <br>
        <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;"><b>Agendar Cita</b></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-4 mt-5">
                    <form action="../../CONTROLLER/citaresi.php" method="post">
                        <fieldset>
                            <center>
                                <legend><b>formulario</b> </legend>
                            </center>
                            <div class="mb-3">
                                <label for="tipocita" class="form-label">Tipo de cita:</label>
                                <select name="tipocita" id="tipocita" class="form-select">
                                    <option selected value="Administrativo">Administrativo (1h)</option>
                                    <option value="Reclamo">Reclamo (1h)</option>
                                    <option value="Duda">Duda (1h)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fechacita" class="form-label">Fecha:</label>
                                <input type="date" class="form-control" id="fechacita" name="fechacita" required>
                            </div>
                            <div class="mb-3">
                                <label for="horacita" class="form-label">Hora:</label>
                                <input type="time" class="form-control" id="horacita" name="horacita" min="08:00" max="17:00" step="3600" required>
                            </div>
                            <div class="mb-3">
                                <label for="apa" class="form-label">Ingresa tu numero de apartamento:</label>
                                <input type="text" class="form-control" id="apa" name="apa" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-8 mt-5">
                    <center>
                        <h2><b>Panel de Citas</b></h2>
                    </center>
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de cita</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Hora</th>
                                <th scope="col">Apartamento</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Comentario</th>
                                <th scope="col">Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cita['tipocita']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['fechacita']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['horacita']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['apa']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['respuesta']); ?></td>
                                    <td>


                                        <form action="" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                            <input type="hidden" name="delete_idcita" value="<?php echo $cita['idcita']; ?>">
                                            <button class="btn btn-danger mt-3 type=" submit" name="delete">Eliminar</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <a href="citas.php" class="btn btn-success">Volver</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
                const fechaInput = document.getElementById('fechacita');
                const horaInput = document.getElementById('horacita');


                const today = new Date();
                const dd = String(today.getDate()).padStart(2, '0');
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const yyyy = today.getFullYear();
                const fechaHoy = yyyy + '-' + mm + '-' + dd;
                fechaInput.setAttribute('min', fechaHoy);


                form.addEventListener('submit', function(e) {
                    const fechaSeleccionada = new Date(fechaInput.value);
                    const horaSeleccionada = horaInput.value;
                    const ahora = new Date();


                    fechaSeleccionada.setHours(0, 0, 0, 0);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);

                    if (fechaSeleccionada < hoy) {
                        alert('No puedes agendar citas en fechas pasadas');
                        e.preventDefault();
                        return false;
                    }


                    if (fechaSeleccionada.getTime() === hoy.getTime()) {
                        const [hora, minutos] = horaSeleccionada.split(':').map(Number);
                        const horaActual = ahora.getHours();
                        const minutoActual = ahora.getMinutes();

                        if (hora < horaActual || (hora === horaActual && minutos < minutoActual)) {
                            alert('No puedes agendar una cita en horario pasado para el día de hoy');
                            e.preventDefault();
                            return false;
                        }
                    }


                    const horaCita = parseInt(horaSeleccionada.split(':')[0]);
                    if (horaCita < 8 || horaCita >= 17) {
                        alert('Las citas solo pueden agendarse entre 8:00 y 17:00 horas');
                        e.preventDefault();
                        return false;
                    }

                    return true;
                });


                fechaInput.addEventListener('change', function() {
                    const fechaSeleccionada = new Date(this.value);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    fechaSeleccionada.setHours(0, 0, 0, 0);

                    if (fechaSeleccionada < hoy) {
                        alert('No puedes seleccionar una fecha pasada');
                        this.value = fechaHoy;
                    }
                });


                horaInput.addEventListener('change', function() {
                    const fechaSeleccionada = new Date(fechaInput.value);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    fechaSeleccionada.setHours(0, 0, 0, 0);


                    if (fechaSeleccionada.getTime() === hoy.getTime()) {
                        const ahora = new Date();
                        const [hora, minutos] = this.value.split(':').map(Number);
                        const horaActual = ahora.getHours();
                        const minutoActual = ahora.getMinutes();

                        if (hora < horaActual || (hora === horaActual && minutos < minutoActual)) {
                            alert('No puedes agendar una cita en horario pasado para hoy');

                            const nuevaHora = horaActual < 17 ? horaActual + 1 : 8;
                            this.value = `${String(nuevaHora).padStart(2, '0')}:00`;
                        }
                    }


                    const hora = parseInt(this.value.split(':')[0]);
                    if (hora < 8 || hora >= 17) {
                        alert('El horario de atención es de 8:00 AM a 17:00 PM horas');
                        this.value = '08:00';
                    }
                });
            });
        </script>
</body>
<br>
<br>
<br>
<br>
<br>
<footer>

    <div class="footer-content">
        <li>&copy; 2025 SETS. Todos los derechos reservados.</li>
        <ul>
            <li><a href="#">Términos y Condiciones</a></li>
            <li><a href="#">Política de Privacidad</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </div>
</footer>

</html>