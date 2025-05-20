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
$sql = "SELECT idcita, tipocita, fechacita, horacita, respuesta FROM cita";
$stmt = $base_de_datos->query($sql);
if (!$stmt) {
    die('Error en la consulta: ' . print_r($base_de_datos->errorInfo(), true));
}
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$eventos = [];
foreach ($citas as $row) {
    $eventos[] = [
        'id' => $row['idcita'],
        'title' => $row['tipocita'],
        'start' => $row['fechacita'] . 'T' . $row['horacita'],
        'respuesta' => $row['respuesta'] 
    ];
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sets - Citas</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/citas.css?v=<?php echo (rand()); ?>">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/ajustes.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
                <b style="font-size: 25px;color:aliceblue"> ADMIN - <?php echo htmlspecialchars($Usuario); ?> </b></a>
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
    <br>
    <br>
    <br>
    <main>
        <section class="anuncio">
            <h2 style="text-align: center;"><b>Citas</b></h2>
        </section>
        <div class="container">
            <div class="calendar-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <h2 id="calendar-title"><b>Calendario de Disponibilidad</b></h2>
                        <p id="month-year" style="color: #0e2c0a;"><b></b></p>
                        <div id="calendar-controls">
                            <button id="prev-month" onclick="prevMonth()">
                                <
                                    <button id="next-month" onclick="nextMonth()">></button>
                        </div>
                    </div>

                    <table id="calendar-table">
                        <thead>
                            <tr>
                                <th>Lu</th>
                                <th>Ma</th>
                                <th>Mi</th>
                                <th>Ju</th>
                                <th>Vi</th>
                                <th>Sa</th>
                                <th>Do</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                            <!-- Las fechas serán generadas aquí por JavaScript -->
                        </tbody>
                    </table>
                    <br>
                    <h2 id="calendar-title" style="font-size: 15px;"><b>Verde : Aceptada , Amarilla:Pendiente , Rojo: Rechazada</b></h2>
                </div>
            </div>


        </div>
    </main>
    <center>

        <a href="citas.php" class="btn btn-success" style="font-size: 30px;">Volver</a>
    </center>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarBody = document.getElementById('calendar-body');
            const eventos = <?php echo json_encode($eventos); ?>;
            const today = new Date();
            let currentYear = today.getFullYear();
            let currentMonth = today.getMonth();
            const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            function generarCalendario(mes, anio) {
                calendarBody.innerHTML = '';
                const firstDay = new Date(anio, mes, 1).getDay();
                const daysInMonth = new Date(anio, mes + 1, 0).getDate();
                let date = 1;
                const totalCells = 42;
                let dayCounter = (firstDay === 0 ? 6 : firstDay - 1);

                for (let i = 0; i < totalCells; i++) {
                    if (i % 7 === 0) {
                        var row = document.createElement('tr');
                    }
                    const cell = document.createElement('td');
                    if (i >= dayCounter && date <= daysInMonth) {
                        const formattedDate = `${anio}-${(mes + 1).toString().padStart(2, '0')}-${date.toString().padStart(2, '0')}`;
                        cell.textContent = date;

                        // Modificamos solo esta parte para manejar los estados
                        eventos.forEach(evento => {
                            if (evento.start.startsWith(formattedDate)) {
                                // Aplicamos clases según la respuesta
                                if (evento.respuesta === 'Aceptada') {
                                    cell.classList.add('celda-aprobada');
                                } else if (evento.respuesta === 'Pendiente') {
                                    cell.classList.add('celda-pendiente');
                                } else if (evento.respuesta === 'Rechazada') {
                                    cell.classList.add('celda-rechazada');
                                }
                                cell.title = evento.title + " - " +
                                    (evento.respuesta === 'Aceptada' ? 'Aceptada' :
                                        evento.respuesta === 'Pendiente' ? 'Pendiente' : 'Rechazada');
                            }
                        });

                        date++;
                    }
                    row.appendChild(cell);

                    if (i % 7 === 6) {
                        calendarBody.appendChild(row);
                    }
                }
                document.getElementById('month-year').textContent = `${months[mes]} ${anio}`;
            }

            function prevMonth() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generarCalendario(currentMonth, currentYear);
            }

            function nextMonth() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generarCalendario(currentMonth, currentYear);
            }
            // Generar el calendario inicial
            generarCalendario(currentMonth, currentYear);

            // Eventos de los botones
            document.getElementById('prev-month').addEventListener('click', prevMonth);
            document.getElementById('next-month').addEventListener('click', nextMonth);
        });
    </script>
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
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