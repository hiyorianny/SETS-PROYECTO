<?php
require __DIR__.'/../../Backend/auth/controller/guarda.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sets - Gymnasio</title>
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
                            <div class="offcanvas-header">
                                <img src="img/pagina-de-inicio.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./inicioprincipal.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Inicio</b></a>
                                </center>
                            </div>
                            <center>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          <img src="img/usuario.png" alt="Logo" width="30" height="34" class="d-inline-block align-text-top">
                               
                                        <b style="font-size: 20px;"> Perfil</b>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <center><a href="Perfil.php"><b>Perfil</b></a></center>
                                        </li>

                                        <li>
                                            <center> <a href="../../Backend/auth/logout.php"><b>Cerrar Sesión</b></a></center>
                                        </li>
                                    </ul>
                            </center
                            </li>
                            <div class="offcanvas-header">
                                <img src="img/notificacion.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="notificaciones.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;">Notificaciones</a>
                                </center>
                            </div>
                          
                        </ul>
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
            <h2>¡Reserva tu espacio! Horarios disponibles - Gymnasio</h2>
        </div>

        <div class="container">
           <div class="calendar-container">
            <div class="calendar">
                <div class="calendar-header">
                    <button onclick="prevMonth()" class="as"><</button>
                    <h2 id="month-year">Mes y Año</h2>
                    <button onclick="nextMonth()" class="as">></button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Lun</th>
                            <th>Mar</th>
                            <th>Mié</th>
                            <th>Jue</th>
                            <th>Vie</th>
                            <th>Sáb</th>
                            <th>Dom</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body"></tbody>
                </table>
                <br>
                   <h2 id="calendar-title" style="font-size: 15px;"><b>Verde :🟩 Aceptada , Amarilla:🟨  Pendiente , Rojo:🟥  Rechazada</b></h2>

            </div>
        </div>
           <aside class="sidebar">
            <h2>Agendaciones</h2>
            <div class="search-bar">
                <input type="search" id="searchInput" placeholder="Buscar ..." />
                <ion-icon name="search-outline"></ion-icon>
            </div>
            <center>
            <div class="appointment-list" id="appointmentList">

                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando solicitudes...</p>
                </div>
            </div>
            </center>
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
        // Variables globales
        let solicitudes = [];
        const zonaId = 5; // ID de la zona BBQ 

        async function cargarSolicitudes() {
            try {
                const response = await fetch(`http://192.168.1.102:3001/api/solicitudes-zonas?zona=${zonaId}`);

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                // Verifica que data sea un array
                if (!Array.isArray(data)) {
                    throw new Error('La respuesta no es un array válido');
                }

                solicitudes = data;

                // Verifica que los elementos del DOM existan antes de usarlos
                const calendarBody = document.getElementById('calendar-body');
                const monthYearDisplay = document.getElementById('month-year');
                const appointmentList = document.getElementById('appointmentList');

                if (!calendarBody || !monthYearDisplay || !appointmentList) {
                    throw new Error('Elementos del DOM no encontrados');
                }

                mostrarSolicitudes();
                generarCalendario();
            } catch (error) {
                console.error('Error:', error);
                const container = document.getElementById('appointmentList') || document.body;
                container.innerHTML = `
            <div class="alert alert-danger">
                Error al cargar: ${error.message}
                <button onclick="cargarSolicitudes()" class="btn btn-sm btn-warning mt-2">
                    Reintentar
                </button>
            </div>
        `;
            }
        }

        // Función para mostrar las solicitudes en el HTML
        function mostrarSolicitudes() {
            const container = document.getElementById('appointmentList');
            let html = '';

            solicitudes.forEach(solicitud => {
                const estadoClass = obtenerClaseEstado(solicitud.estado);

                html += `
                    <div class="appointment"
                        data-fecha-inicio="${formatearFecha(solicitud.fechainicio)}"
                        data-fecha-final="${formatearFecha(solicitud.fechafinal)}"
                        data-hora-inicio="${formatearHora(solicitud.Hora_inicio)}"
                        data-hora-final="${formatearHora(solicitud.Hora_final)}"
                        data-apartamento="${solicitud.ID_Apartamentooss}"
                        data-estado="${solicitud.estado.toLowerCase()}">
                        <h3>GYM</h3>
                        <p><strong>Fecha Inicio:</strong> ${formatearFecha(solicitud.fechainicio)}</p>
                        <p><strong>Fecha Final:</strong> ${formatearFecha(solicitud.fechafinal)}</p>
                        <p><strong>Hora_inicio:</strong> ${formatearHora(solicitud.Hora_inicio)}</p>
                        <p><strong>Hora_final:</strong> ${formatearHora(solicitud.Hora_final)}</p>
                        <p><strong>Apartamento:</strong> ${solicitud.ID_Apartamentooss}</p>
                        <p><strong>SOLICITUD FUE:</strong>
                            <span class="badge ${estadoClass}">
                                ${solicitud.estado}
                            </span>
                        </p>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Inicializar el buscador
            inicializarBuscador();
        }

        // Función para generar el calendario
        function generarCalendario() {
            const calendarBody = document.getElementById('calendar-body');
            const monthYearDisplay = document.getElementById('month-year');
            const today = new Date();
            const months = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            let currentYear = today.getFullYear();
            let currentMonth = today.getMonth();

            // Función interna para generar el calendario
            function generarMes(mes, anio) {
                calendarBody.innerHTML = '';
                monthYearDisplay.textContent = `${months[mes]} ${anio}`;
                const firstDayOfMonth = new Date(anio, mes, 1).getDay() || 7;
                const daysInMonth = new Date(anio, mes + 1, 0).getDate();
                let date = 1;

                // Mapa de fechas con estados
                const fechasConEstado = {};
                solicitudes.forEach(solicitud => {
                    const fecha = new Date(solicitud.fechainicio).toISOString().split('T')[0];
                    fechasConEstado[fecha] = solicitud.estado.toUpperCase();
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

                            if (fechasConEstado[fechaActualStr]) {
                                const estado = fechasConEstado[fechaActualStr];
                                cell.classList.add(`estado-${estado.toLowerCase()}`);
                                cell.setAttribute('title', `Estado: ${estado}`);
                            }

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

            // Funciones de navegación del calendario
            window.prevMonth = function() {
                currentMonth = (currentMonth - 1 + 12) % 12;
                if (currentMonth === 11) currentYear--;
                generarMes(currentMonth, currentYear);
            }

            window.nextMonth = function() {
                currentMonth = (currentMonth + 1) % 12;
                if (currentMonth === 0) currentYear++;
                generarMes(currentMonth, currentYear);
            }

            // Inicializar calendario
            generarMes(currentMonth, currentYear);
        }

        // Función para inicializar el buscador
        function inicializarBuscador() {
            const searchInput = document.getElementById('searchInput');
            const appointments = document.querySelectorAll('.appointment');

            function filterAppointments(searchText) {
                Array.from(appointments).forEach(appointment => {
                    const textoAppointment = appointment.textContent.toLowerCase();
                    appointment.style.display = textoAppointment.includes(searchText) ? 'block' : 'none';
                });
            }

            searchInput.addEventListener('input', function() {
                filterAppointments(this.value.toLowerCase());
            });
        }

        // Funciones auxiliares
        function formatearFecha(fechaString) {
            const fecha = new Date(fechaString);
            return fecha.toLocaleDateString('es-ES');
        }

        function formatearHora(horaString) {
            const hora = new Date(`2000-01-01T${horaString}`);
            return hora.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function obtenerClaseEstado(estado) {
            estado = estado.toLowerCase();
            if (estado === 'aprobado') return 'bg-success';
            if (estado === 'pendiente') return 'bg-warning';
            if (estado === 'rechazado') return 'bg-danger';
            return 'bg-secondary';
        }

        // Cargar las solicitudes cuando la página esté lista
        document.addEventListener('DOMContentLoaded', cargarSolicitudes);
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