<?php
require __DIR__ . '/../../Backend/auth/controller/admin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Control de Citas</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/citasFormularioAdm.css?v=<?php echo (rand()); ?>">
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
                            <center>
                                <li class="nav-item dropdown">
                                    <img src="img/hablando.png" alt="Logo" width="30" height="44" class="d-inline-block align-text-top" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <b style="font-size: 20px;"> CHAT</b>

                                    <ul class="dropdown-menu" role="menu">

                                        <li>
                                            <center><a href="#" class="chat-item" onclick="openChat('Guarda de Seguridad')">Guarda de Seguridad</a></center>
                                        </li>
                                        <li>
                                            <center><a href="#" class="chat-item" onclick="openChat('Residente')">Residente</a></center>
                                        </li>
                                        <li>
                                            <center><a href="#" class="chat-item" onclick="openChat('Chat Comunal')">Chat Comunal</a></center>
                                        </li>
                                    </ul>
                            </center>
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
    <br><br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 mt-5">
                <div class="alert alert-success" role="alert">
                    <center>
                        <h2><b>Control de Citas</b></h2>
                    </center>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Caracter</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Respuesta</th>
                            <th>Acciones</th>
                            <th>Proceso</th>
                        </tr>
                    </thead>
                    <tbody id="citasTableBody">
                        <!-- Las citas se cargarán aquí mediante JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-success" href="CALEN.php">Ver Calendario</a>
        </div>
        <br>

        <div class="container">
            <a class="btn btn-success" href="inicioprincipal.php">Volver</a>
        </div>
        <br>
    </div>

    <script>
        async function loadCitas() {
            try {
                const response = await fetch('http://192.168.1.102:3001/api/citas');
                if (!response.ok) {
                    throw new Error('Error al cargar las citas');
                }
                const citas = await response.json();
                renderCitas(citas);
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar las citas');
            }
        }


        function renderCitas(citas) {
            const tableBody = document.getElementById('citasTableBody');
            tableBody.innerHTML = '';

            citas.forEach(cita => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${escapeHtml(cita.tipocita)}</td>
                    <td>${escapeHtml(cita.fechacita)}</td>
                    <td>${escapeHtml(cita.horacita)}</td>
                    <td>${escapeHtml(cita.estado)}</td>
                    <td>${escapeHtml(cita.respuesta || '')}</td>
                    <td>
                        <button class="btn btn-danger mt-3" onclick="deleteCita(${cita.idcita})">Eliminar</button>
                    </td>
                    <td>
                        ${cita.estado === 'pendiente' ? 
                            `<form onsubmit="responderCita(event, ${cita.idcita})">
                                <textarea name="respuesta" required placeholder="Escribe tu respuesta aquí"></textarea>
                                <button class="btn btn-success" type="submit">Enviar Respuesta</button>
                            </form>` : 
                            '<span>Respondida</span>'}
                    </td>
                `;

                tableBody.appendChild(row);
            });
        }

        // Función para responder a una cita
        async function responderCita(event, idcita) {
            event.preventDefault();
            const respuesta = event.target.respuesta.value;

            try {
                const response = await fetch('http://192.168.1.102:3001/api/citas/responder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        idcita,
                        respuesta
                    })
                });

                if (!response.ok) {
                    throw new Error('Error al responder la cita');
                }

                alert('Respuesta enviada con éxito');
                loadCitas(); // Recargar las citas
            } catch (error) {
                console.error('Error:', error);
                alert('Error al responder la cita');
            }
        }


        async function deleteCita(idcita) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
                return;
            }

            try {
                const response = await fetch(`http://192.168.1.102:3001/api/citas/${idcita}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    throw new Error('Error al eliminar la cita');
                }

                alert('Cita eliminada con éxito');
                loadCitas(); // Recargar las citas
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la cita');
            }
        }

        // Función para escapar HTML (seguridad)
        function escapeHtml(unsafe) {
            if (unsafe == null) return '';
            return unsafe.toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Cargar citas al cargar la página
        document.addEventListener('DOMContentLoaded', loadCitas);

        // Funciones del chat (mantenidas igual)
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
    </script>
    <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>