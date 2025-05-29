<?php
require __DIR__ . '/../../Backend/auth/controller/guarda.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - NOTIFICACIONES</title>
    <link rel="stylesheet" href="css/notificaciones.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body>
    <header>
        <div class="topbar">
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
    </div>
    </header>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
   <div class="container">
        <center>
            <div class="alert alert-success" role="alert" style="font-size: 34px;">
                <b> NOTIFICACIONES</b>
            </div>
        </center>
        <div class="email-list" id="notifications-container">
            <div class="text-center mt-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando notificaciones...</span>
                </div>
                <p>Cargando notificaciones...</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const apiBaseUrl = 'http://192.168.1.102:3001/api';
        const usuario = "<?php echo htmlspecialchars($Usuario); ?>";
        const idRegistro = "<?php echo $idRegistro; ?>";
        let hiddenNotifications = JSON.parse(localStorage.getItem("hiddenNotifications_" + usuario)) || [];
        let lastCheckTime = localStorage.getItem("lastCheckTime_" + usuario) || new Date(0).toISOString();

        // Función para formatear fechas
        function formatDate(dateString) {
            if (!dateString) return 'Fecha no disponible';
            const date = new Date(dateString);
            return date.toLocaleString('es-ES');
        }

        // Función para escapar HTML
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe.toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Función para mostrar notificación con SweetAlert2
        function showNotificationAlert(title, message, icon = 'info') {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }

        // Función para obtener datos de la API con manejo de errores
        async function fetchApiData(endpoint) {
            try {
                const response = await fetch(`${apiBaseUrl}${endpoint}`);
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('Error fetching data from', endpoint, error);

                return [];
            }
        }

        // Función para crear elementos de notificación
        function createNotificationElement(item, type) {
            const notifId = `${type}_${item.idAnuncio || item.id_solicitud || item.ID_zonaComun || item.id_mensaje}`;
            
            if (hiddenNotifications.includes(notifId)) return null;

            const element = document.createElement('div');
            element.className = 'email-item';
            element.setAttribute('data-id', notifId);

            let htmlContent = '';
            let actionUrl = 'inicioprincipal.php';
            let isNew = false;

            // Verificar si es una notificación nueva
            const itemDate = new Date(item.fechaPublicacion || item.fecha_inicio || item.fechainicio || item.fecha_envio);
            const checkDate = new Date(lastCheckTime);
            isNew = itemDate > checkDate;

            switch (type) {
                case 'anuncio':
                    htmlContent = `
                        <div class="email-sender ${isNew ? 'text-primary fw-bold' : ''}">Anuncio: ${escapeHtml(item.titulo)}</div>
                        <div class="email-subject">Publicado el: ${formatDate(item.fechaPublicacion)}</div>
                        <div class="email-snippet">Descripción: ${escapeHtml(item.descripcion)}</div>
                        <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                        <a href="${actionUrl}" class="btn btn-outline-success" style="font-size:15px;">
                            <center>IR</center>
                        </a>
                    `;
                    break;

                case 'parqueadero':
                    actionUrl = item.TipoVehiculo === 'Carro' ? './parqueaderocarro.php' : './paromoto.php';
                    htmlContent = `
                        <b class="${isNew ? 'text-primary' : ''}">Solicitud de Parqueadero</b><br>
                        <b>Tipo Vehiculo:</b> ${escapeHtml(item.TipoVehiculo)}<br>
                        <b>Fecha Inicio:</b> ${formatDate(item.fecha_inicio)}<br>
                        <b>Parqueadero:</b> ${escapeHtml(item.parqueadero_visitante)}<br>
                        <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                        <a href="${actionUrl}" class="btn btn-outline-success" style="font-size:15px;">
                            <center>IR</center>
                        </a>
                    `;
                    break;

                case 'zona':
                    actionUrl = './zonas_comunes.php';
                    htmlContent = `
                        <b class="${isNew ? 'text-primary' : ''}">Solicitud de Zona Común</b><br>
                        <b>ID:</b> ${escapeHtml(item.ID_zonaComun)}<br>
                        <b>Inicio:</b> ${formatDate(item.fechainicio)}<br>
                        <b>Final:</b> ${formatDate(item.fechafinal)}<br>
                        <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                        <a href="${actionUrl}" class="btn btn-outline-success" style="font-size:15px;">
                            <center>IR</center>
                        </a>
                    `;
                    break;

                case 'mensaje':
                    actionUrl = 'inicioprincipal.php';
                    htmlContent = `
                        <b class="${isNew ? 'text-primary' : ''}">Nuevo Mensaje</b><br>
                        <b>De:</b> ${escapeHtml(item.id_remitente)}<br>
                        <b>Fecha:</b> ${formatDate(item.fecha_envio)}<br>
                        <b>Mensaje:</b> ${escapeHtml(item.contenido)}<br>
                        <button class="btn btn-sm btn-danger remove-notif">Descartar</button>
                        <a href="${actionUrl}" class="btn btn-outline-success" style="font-size:15px;">
                            <center>IR AL CHAT</center>
                        </a>
                    `;
                    break;
            }

            element.innerHTML = htmlContent;
            
            // Configurar evento para el botón de descartar
            const removeBtn = element.querySelector('.remove-notif');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    hiddenNotifications.push(notifId);
                    localStorage.setItem("hiddenNotifications_" + usuario, JSON.stringify(hiddenNotifications));
                    element.style.display = 'none';
                    showNotificationAlert('Notificación descartada', 'La notificación ha sido eliminada', 'success');
                });
            }

            return element;
        }

        // Función principal para cargar notificaciones
        async function loadNotifications() {
            const container = document.getElementById('notifications-container');
            
            try {
                // Mostrar spinner de carga
                container.innerHTML = `
                    <div class="text-center mt-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando notificaciones...</span>
                        </div>
                        <p>Cargando notificaciones...</p>
                    </div>
                `;

                // Obtener datos de la API
                const [anuncios, parqueaderos, zonas, mensajes] = await Promise.all([
                    fetchApiData('/anuncios'),
                    fetchApiData('/solicitudes-parqueadero?limit=5'),
                    fetchApiData('/solicitudes-zonas?limit=5'),
                    fetchApiData(`/mensajes-chat?id_destinatario=${idRegistro}&limit=5`)
                ]);

                // Limpiar contenedor
                container.innerHTML = '';

                // Procesar todas las notificaciones
                const allNotifications = [
                    ...anuncios.map(item => ({ item, type: 'anuncio' })),
                    ...parqueaderos.map(item => ({ item, type: 'parqueadero' })),
                    ...zonas.map(item => ({ item, type: 'zona' })),
                    ...mensajes.map(item => ({ item, type: 'mensaje' }))
                ];

                // Ordenar por fecha (más recientes primero)
                allNotifications.sort((a, b) => {
                    const dateA = new Date(a.item.fechaPublicacion || a.item.fecha_inicio || a.item.fechainicio || a.item.fecha_envio);
                    const dateB = new Date(b.item.fechaPublicacion || b.item.fecha_inicio || b.item.fechainicio || b.item.fecha_envio);
                    return dateB - dateA;
                });

                if (allNotifications.length === 0) {
                    container.innerHTML = '<div class="text-center py-5"><p>No hay notificaciones nuevas</p></div>';
                    return;
                }

                // Mostrar notificaciones
                allNotifications.forEach(({ item, type }) => {
                    const element = createNotificationElement(item, type);
                    if (element) {
                        container.appendChild(element);
                        
                        // Mostrar alerta para notificaciones nuevas
                        const itemDate = new Date(item.fechaPublicacion || item.fecha_inicio || item.fechainicio || item.fecha_envio);
                        const checkDate = new Date(lastCheckTime);
                        
                        if (itemDate > checkDate) {
                            let title = '';
                            let message = '';
                            
                            switch (type) {
                                case 'anuncio':
                                    title = 'Nuevo anuncio';
                                    message = item.titulo;
                                    break;
                                case 'parqueadero':
                                    title = 'Nueva solicitud de parqueadero';
                                    message = `Para  ${item.parqueadero_visitante}`;
                                    break;
                                case 'zona':
                                    title = 'Nueva solicitud de zona común';
                                    message = `ID: ${item.ID_zonaComun}`;
                                    break;
                                case 'mensaje':
                                    title = 'Nuevo mensaje';
                                    message = `De: ${item.id_remitente}`;
                                    break;
                            }
                            
                            showNotificationAlert(title, message);
                        }
                    }
                });

                // Actualizar última hora de verificación
                lastCheckTime = new Date().toISOString();
                localStorage.setItem("lastCheckTime_" + usuario, lastCheckTime);

            } catch (error) {
                console.error('Error al cargar notificaciones:', error);
                container.innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar notificaciones. Por favor recarga la página.
                    </div>
                `;

            }
        }

        // Inicializar
        loadNotifications();
        
        // Actualizar periódicamente (cada 30 segundos)
        setInterval(loadNotifications, 30000);
    });
    </script>

    </main>


    <script>
        function toggleExpand(element) {
            element.classList.toggle('expanded');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size:30px;   background-color: #0e2c0a;">
            <center>VOLVER</center>
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<br>
<br>
<br>
<br>

</html>