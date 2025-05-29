<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sets - Entrada GYM</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/citas.css?v=<?php echo (rand()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
                            </center>
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
    <br> <br>
    <main>
        <br> <br> <br>
        <div class="alert alert-success g" role="alert">
            <h2>Horarios Disponibles - Gimnasio <br> Numero de la Zona : 5</h2>
        </div>

       <div class="container">
            <div class="calendar-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <h2 id="calendar-title"><b>Calendario de Disponibilidad</b></h2>
                        <br>
                        <div id="calendar-controls">
                            <span id="month-year" style="color: #0e2c0a;"><b></b></span>

                            <button id="prev-month" class="btn btn-primary">
                                <</button>

                                    <button id="next-month" class="btn btn-primary">></button>
                        </div>
                    </div>
                    <table id="calendar-table" class="table table-bordered">
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

                        </tbody>
                    </table>
                    <br>
                    <h2 id="calendar-title" style="font-size: 15px;"><b>Verde :🟩 Aceptada , Amarilla:🟨 Pendiente , Rojo:🟥 Rechazada</b><p>Si su estado a sido aceptada tomele una foto y ya sera valida</p></h2>
                </div>
            </div>

            <aside class="sidebar">
                <h2>Reservadas</h2>
                <div class="search-bar">
                    <input type="search" id="searchInput" placeholder="Buscar ..." class="form-control" />
                </div>
                <div class="appointment-list" id="appointmentList">

                </div>
            </aside>
        </div>

        <a href="zonas_comunes.php" class="btn btn-success" style="font-size: 30px;">
            <center>Volver</center>
        </a>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let solicitudes = [];
        const ZONA_ID = 5;


        async function loadSolicitudes() {
            try {
                const response = await fetch(`http://192.168.1.102:3001/api/solicitudes-zonas?zona=${ZONA_ID}`);

                if (!response.ok) {
                    throw new Error('Error al cargar las solicitudes');
                }

                solicitudes = await response.json();
                displaySolicitudes();
                generarCalendario(currentMonth, currentYear);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('appointmentList').innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar las solicitudes: ${error.message}
                    </div>
                `;
            }
        }


        function displaySolicitudes() {
            const container = document.getElementById('appointmentList');
            container.innerHTML = '';

            if (solicitudes.length === 0) {
                container.innerHTML = '<p>No hay solicitudes para esta zona</p>';
                return;
            }

            solicitudes.forEach(solicitud => {
                const fechaInicio = formatDate(solicitud.fechainicio);
                const fechaFinal = formatDate(solicitud.fechafinal);
                const horaInicio = formatTime(solicitud.Hora_inicio);
                const horaFinal = formatTime(solicitud.Hora_final);
                const estado = solicitud.estado.toUpperCase();

                const estadoClass = {
                    'ACEPTADA': 'bg-success',
                    'PENDIENTE': 'bg-warning',
                    'RECHAZADA': 'bg-danger'
                } [estado] || 'bg-secondary';

                const appointmentDiv = document.createElement('div');
                appointmentDiv.className = 'appointment mb-3 p-3 border rounded';
                appointmentDiv.setAttribute('data-fecha-inicio', fechaInicio);
                appointmentDiv.setAttribute('data-fecha-final', fechaFinal);
                appointmentDiv.setAttribute('data-hora-inicio', horaInicio);
                appointmentDiv.setAttribute('data-hora-final', horaFinal);
                appointmentDiv.setAttribute('data-apartamento', solicitud.ID_Apartamentooss);
                appointmentDiv.setAttribute('data-estado', estado);


                appointmentDiv.innerHTML = `
            <h3><b>GYM</b></h3>
            <p><strong>Fecha Inicio:</strong> ${fechaInicio}</p>
            <p><strong>Fecha Final:</strong> ${fechaFinal}</p>
            <p><strong>Hora inicio:</strong> ${horaInicio}</p>
            <p><strong>Hora final:</strong> ${horaFinal}</p>
            <p><strong>Apartamento:</strong> ${solicitud.ID_Apartamentooss}</p>
            <p><strong>Estado:</strong> <span class="badge ${estadoClass}">${estado}</span></p>
            <div class="btn-group" role="group">
                <button class="btn btn-success" onclick="editarSolicitud('${solicitud.ID_Apartamentooss}', ${solicitud.ID_zonaComun})">
                    Editar
                </button>
                <button class="btn btn-danger" onclick="eliminarSolicitud('${solicitud.ID_Apartamentooss}', ${solicitud.ID_zonaComun})">
                    Eliminar
                </button>

            </div>
        `;

                container.appendChild(appointmentDiv);
            });
        }

        function editarSolicitud(ID_Apartamentooss, ID_zonaComun) {

            const solicitud = solicitudes.find(s =>
                s.ID_Apartamentooss === ID_Apartamentooss &&
                s.ID_zonaComun === ID_zonaComun
            );

            if (solicitud) {
                sessionStorage.setItem('solicitudEditar', JSON.stringify(solicitud));
                window.location.href = `editarsolicitudgym.php?ID_Apartamentooss=${ID_Apartamentooss}&ID_zonaComun=${ID_zonaComun}`;
            } else {
                alert('No se encontró la solicitud para editar');
            }
        }


        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        }

        function formatTime(timeString) {
            const time = new Date(`1970-01-01T${timeString}`);
            return time.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        async function cambiarEstado(accion, ID_Apartamentooss) {
            try {

                if (!ID_Apartamentooss) {
                    throw new Error('ID de apartamento no proporcionado');
                }

                let nuevoEstado;
                switch (accion) {
                    case 'ACEPTADA':
                        nuevoEstado = 'ACEPTADA';
                        break;
                    case 'PENDIENTE':
                        nuevoEstado = 'PENDIENTE';
                        break;
                    case 'RECHAZADA':
                        nuevoEstado = 'RECHAZADA';
                        break;
                    default:
                        throw new Error('Acción no válida');
                }

                console.log(`Cambiando estado de solicitud ${ID_Apartamentooss} a ${nuevoEstado}`);

                const response = await fetch(`http://192.168.1.102:3001/api/solicitudes-zonas/${ID_Apartamentooss}/actualizar-estado`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                });


                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'Error al cambiar el estado');
                }

                if (!result.success) {
                    throw new Error('No se pudo actualizar el estado');
                }

                alert('Estado cambiado con éxito');
                loadSolicitudes();
            } catch (error) {
                console.error('Error en cambiarEstado:', error);
                alert(`Error: ${error.message}`);
            }
        }
        async function eliminarSolicitud(ID_Apartamentooss, ID_zonaComun) {
            const solicitud = solicitudes.find(s =>
                s.ID_Apartamentooss === ID_Apartamentooss &&
                s.ID_zonaComun === ID_zonaComun
            );

            if (!solicitud) {
                alert('No se encontró la solicitud para eliminar');
                return;
            }

            if (confirm('¿Estás seguro de eliminar esta solicitud?')) {
                try {
                    // Formatear fecha correctamente para el backend
                    const fechaOriginal = new Date(solicitud.fechainicio);
                    const fechaFormateada = fechaOriginal.toISOString().split('T')[0];

                    const response = await fetch('http://192.168.1.102:3001/api/solicitudes-zonas/cancelar', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ID_Apartamentooss: ID_Apartamentooss,
                            ID_zonaComun: ID_zonaComun,
                            fechainicio: fechaFormateada,
                            Hora_inicio: solicitud.Hora_inicio
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Error al eliminar la solicitud');
                    }

                    if (result.success) {
                        alert('Solicitud eliminada correctamente');
                        loadSolicitudes();
                    } else {
                        throw new Error(result.error || 'No se pudo eliminar la solicitud');
                    }
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    alert('Error al eliminar: ' + error.message);
                }
            }
        }
     

        const calendarBody = document.getElementById('calendar-body');
        const monthYearDisplay = document.getElementById('month-year');
        const today = new Date();
        const months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth();

        function generarCalendario(mes, anio) {
            calendarBody.innerHTML = '';
            monthYearDisplay.textContent = `${months[mes]} ${anio}`;
            const firstDayOfMonth = new Date(anio, mes, 1).getDay() || 7;
            const daysInMonth = new Date(anio, mes + 1, 0).getDate();
            let date = 1;


            const fechasConEstado = {};
            solicitudes.forEach(solicitud => {
                const fecha = new Date(solicitud.fechainicio).toISOString().split('T')[0];
                fechasConEstado[fecha] = solicitud.estado.toUpperCase();
            });

            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');

                for (let j = 1; j <= 7; j++) {
                    const cell = document.createElement('td');
                    cell.className = 'text-center p-2';

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
                            if (estado === 'ACEPTADA') {
                                cell.classList.add('estado-aceptada');
                            } else if (estado === 'PENDIENTE') {
                                cell.classList.add('estado-pendiente');
                            } else if (estado === 'RECHAZADA') {
                                cell.classList.add('estado-rechazada');
                            }
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


        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const appointments = document.querySelectorAll('.appointment');

                appointments.forEach(appointment => {
                    const fechaInicio = appointment.getAttribute('data-fecha-inicio').toLowerCase();
                    const fechaFinal = appointment.getAttribute('data-fecha-final').toLowerCase();
                    const horaInicio = appointment.getAttribute('data-hora-inicio').toLowerCase();
                    const horaFinal = appointment.getAttribute('data-hora-final').toLowerCase();
                    const apartamento = appointment.getAttribute('data-apartamento').toLowerCase();
                    const estado = appointment.getAttribute('data-estado').toLowerCase();

                    if ([fechaInicio, fechaFinal, horaInicio, horaFinal, apartamento, estado].some(
                            text => text.includes(searchText)
                        )) {
                        appointment.style.display = 'block';
                    } else {
                        appointment.style.display = 'none';
                    }
                });
            });
        }


        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('prev-month').addEventListener('click', prevMonth);
            document.getElementById('next-month').addEventListener('click', nextMonth);


            setupSearch();


            loadSolicitudes();


            generarCalendario(currentMonth, currentYear);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>