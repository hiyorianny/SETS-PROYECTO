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


if ($idRol != 2222) {
    header("Location: http://localhost/sets/error.php");
    exit();
}

include_once "conexion.php";


$sql = "SELECT *  FROM solicitud_zona sz  WHERE sz.ID_zonaComun = 3;
";
$stmt = $base_de_datos->query($sql);
$solicitudes = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $solicitudes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sets - SALON COMUNAL</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/citas.css?v=<?php echo (rand()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/guarda.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
                <b style="font-size: 30px;color:aliceblue"> Guarda de Seguridad - <?php echo htmlspecialchars($Usuario); ?> </b></a>
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
    <br><br>
    <main>
        <br>
        <br>
        <div class="alert alert-success g" role="alert">
            <h2>¡Reserva tu espacio! Horarios Disponibles - SALON COMUNAL</h2>
        </div>
        <div class="container">
            <div class="calendar-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <h2 id="calendar-title">Calendario de Disponibilidad</h2>
                        <br>
                        <p>
                            <span id="month-year" style="color: #0e2c0a;"><b></b></span>
                        <div id="calendar-controls">
                            <button id="prev-month" onclick="prevMonth()">
                                <
                                    <span id="month-year"></span>
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
            <aside class="sidebar">
                <h2>Agendadas</h2>
                <div class="search-bar">
                    <input type="search" id="searchInput" placeholder="Buscar ..." />
                    <ion-icon name="search-outline"></ion-icon>
                </div>
                <div class="appointment-list" id="appointmentList">
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <div class="appointment"
                            data-fecha-inicio="<?= date('d/m/Y', strtotime($solicitud['fechainicio'])) ?>"
                            data-fecha-final="<?= date('d/m/Y', strtotime($solicitud['fechafinal'])) ?>"
                            data-hora-inicio="<?= date('h:i A', strtotime($solicitud['Hora_inicio'])) ?>"
                            data-hora-final="<?= date('h:i A', strtotime($solicitud['Hora_final'])) ?>"
                            data-apartamento="<?= $solicitud['ID_Apartamentooss'] ?>"
                            data-estado="<?= $solicitud['estado'] ?>">
                            <h3>SALON COMUNAL</h3>
                            <p><strong>Fecha Inicio:</strong> <?= date('d/m/Y', strtotime($solicitud['fechainicio'])) ?></p>
                            <p><strong>Fecha Final:</strong> <?= date('d/m/Y', strtotime($solicitud['fechafinal'])) ?></p>
                            <p><strong>Hora_inicio:</strong> <?= date('h:i A', strtotime($solicitud['Hora_inicio'])) ?></p>
                            <p><strong>Hora_final:</strong> <?= date('h:i A', strtotime($solicitud['Hora_final'])) ?></p>
                            <p><strong>Apartamento:</strong> <?= $solicitud['ID_Apartamentooss'] ?></p>
                            <p><strong>SOLICITUD FUE:</strong>
                                <span class="badge 
                                    <?php
                                    switch (strtolower($solicitud['estado'])) {
                                        case 'aprobado':
                                            echo 'bg-success';
                                            break;
                                        case 'pendiente':
                                            echo 'bg-warning';
                                            break;
                                        case 'rechazado':
                                            echo 'bg-danger';
                                            break;
                                        default:
                                            echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?= $solicitud['estado'] ?>
                                </span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>
        <a href="zonas_comunes.php" class="btn btn-success" style="font-size: 30px;">
            <center>VOLVER</center>
        </a>
        <div id="chatContainer" class="chat-container">
            <div class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </div>
            <div class="chat-messages" id="chatMessages">
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </main>
    <script>
        // Convertir los datos de PHP a JavaScript
        const solicitudes = <?php echo json_encode($solicitudes); ?>;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarBody = document.getElementById('calendar-body');
            const monthYearDisplay = document.getElementById('month-year');
            const today = new Date();
            const months = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            let currentYear = today.getFullYear();
            let currentMonth = today.getMonth();

            // Función para generar el calendario del mes y año dados
            function generarCalendario(mes, anio) {
                calendarBody.innerHTML = '';
                monthYearDisplay.textContent = `${months[mes]} ${anio}`;
                const firstDayOfMonth = new Date(anio, mes, 1).getDay() || 7;
                const daysInMonth = new Date(anio, mes + 1, 0).getDate();
                let date = 1;

                // Crear un mapa de fechas con sus estados para mejor performance
                const fechasConEstado = {};
                solicitudes.forEach(solicitud => {
                    const fecha = new Date(solicitud.fechainicio).toISOString().split('T')[0];
                    fechasConEstado[fecha] = solicitud.estado.toUpperCase(); // Aseguramos mayúsculas
                });

                for (let i = 0; i < 6; i++) {
                    const row = document.createElement('tr');

                    for (let j = 1; j <= 7; j++) {
                        const cell = document.createElement('td');

                        if (i === 0 && j < firstDayOfMonth) {
                            cell.innerHTML = '';
                        } else if (date > daysInMonth) {
                            break;
                        } else {
                            const fechaActual = new Date(anio, mes, date);
                            const fechaActualStr = fechaActual.toISOString().split('T')[0];

                            cell.textContent = date;
                            cell.setAttribute('data-date', fechaActualStr);

                            // Verificar si la fecha tiene una solicitud
                            if (fechasConEstado[fechaActualStr]) {
                                const estado = fechasConEstado[fechaActualStr];

                                // Aplicar clases según el estado
                                if (estado === 'ACEPTADA') {
                                    cell.classList.add('estado-aceptada');
                                } else if (estado === 'PENDIENTE') {
                                    cell.classList.add('estado-pendiente');
                                } else if (estado === 'RECHAZADA' || estado === 'RECHAZADO') {
                                    cell.classList.add('estado-rechazada');
                                }

                                // Tooltip con información
                                cell.setAttribute('title', `Estado: ${estado}`);
                            }

                            // Resaltar fines de semana
                            if (j === 6 || j === 7) {
                                cell.classList.add('fin-de-semana');
                            }

                            date++;
                        }
                        row.appendChild(cell);
                    }
                    calendarBody.appendChild(row);
                }
            }

            function prevMonth() {
                currentMonth = (currentMonth - 1 + 12) % 12;
                if (currentMonth === 11) currentYear--;
                generarCalendario(currentMonth, currentYear);
            }

            function nextMonth() {
                currentMonth = (currentMonth + 1) % 12;
                if (currentMonth === 0) currentYear++;
                generarCalendario(currentMonth, currentYear);
            }

            // Inicializar calendario
            generarCalendario(currentMonth, currentYear);

            // Event listeners
            document.getElementById('prev-month').addEventListener('click', prevMonth);
            document.getElementById('next-month').addEventListener('click', nextMonth);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const appointmentList = document.getElementById('appointmentList');
            const appointments = appointmentList.getElementsByClassName('appointment');

            // Función para filtrar 
            function filterAppointments(searchText) {
                Array.from(appointments).forEach(function(appointment) {
                    const fechaInicio = appointment.getAttribute('data-fecha-inicio').toLowerCase();
                    const fechaFinal = appointment.getAttribute('data-fecha-final').toLowerCase();
                    const horaInicio = appointment.getAttribute('data-hora-inicio').toLowerCase();
                    const horaFinal = appointment.getAttribute('data-hora-final').toLowerCase();
                    const apartamento = appointment.getAttribute('data-apartamento').toLowerCase();
                    const estado = appointment.getAttribute('data-estado').toLowerCase();

                    if (
                        fechaInicio.includes(searchText) ||
                        fechaFinal.includes(searchText) ||
                        horaInicio.includes(searchText) ||
                        horaFinal.includes(searchText) ||
                        apartamento.includes(searchText) ||
                        estado.includes(searchText)
                    ) {
                        appointment.style.display = 'block'; // Muestra
                    } else {
                        appointment.style.display = 'none'; // Oculta 
                    }
                });
            }

            searchInput.addEventListener('input', function() {
                const searchText = searchInput.value.toLowerCase();
                filterAppointments(searchText);
            });


            filterAppointments('');
        });
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <br>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SETS. Todos los derechos reservados.</p>
            <ul>
                <li><a href="#">Términos y Condiciones</a></li>
                <li><a href="#">Política de Privacidad</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>