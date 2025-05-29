<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Residente</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/principal.css?v=<?php echo (rand()); ?>">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
                </a>

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
                            <br>
                            <center>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="img/hablando.png" alt="Logo" width="30" height="44" class="d-inline-block align-text-top">
                                        <b style="font-size: 20px;">CHAT</b>

                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-chat dropdown-menu-end" id="chatDropdownMenu" role="menu">
                                        <li>
                                            <div class="chat-search-container p-2">
                                                <input type="text" class="form-control form-control-sm chat-search-input"
                                                    placeholder="Buscar contacto..." oninput="filterChatContacts()">
                                            </div>
                                        </li>
                                        <li class="dropdown-header">Contactos</li>
                                    </ul>
                                </li>
                            </center>
                            
                            <div class="offcanvas-header">
                                <img src="img/notificacion.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">


                                <center>
                                    <a href="notificaciones.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;">Notificaciones</a>
                                </center>
                            </div>
                           <div class="offcanvas-header">
                                <img src="img/reporte-de-negocios.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./informes.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Informes</b></a>
                                </center>
                            </div>

                            <div class="offcanvas-header">
                                <img src="img/ayudar (1).png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./ayuda.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Ayuda</b></a>
                                </center>
                            </div>
                        </ul>
                     
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <br><br>
    <main>
        <div id="chatContainer" class="chat-container">
            <div class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </div>
            <div class="chat-messages" id="chatMessages">
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" style="font-size: 14px;" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
        </header>
        <br><br>
        <br><br>
        <main>
            <div class="container text-center">
                <div class="row">
                    <br>
                    <div class="col-12 col-sm-6  ">
                        <div class="icon">
                            <a href="manualconvivencia.php" class="link-button">
                                <img src="img/instrucciones.png" alt="Manual de convivencia" class="medium-img">
                                <button class="add-announcement">Manual de Convivencia</button>
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="col-12 col-sm-6 ">
                        <div class="icon">
                            <a href="parqueaderocarro.php" class="link-button">
                                <img src="img/coche.png" alt="Parqueadero" class="medium-img">
                                <button class="add-announcement">Parqueadero</button>
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="col-12 col-sm-6 ">
                        <div class="icon">
                            <a href="zonas_comunes.php" class="link-button">
                                <img src="img/campo.png" alt="Zonas Comunes" class="medium-img">
                                <button class="add-announcement">Zonas Comunes</button>
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="col-12 col-sm-6 ">
                        <div class="icon">
                            <a href="citas.php" class="link-button">
                                <img src="img/citas.png" alt="Citas con amd" class="medium-img">
                                <button class="add-announcement">Citas </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        </div>
        </div>
        </div>
        </div>
        </div>
        <br>
        <br><br>
          <br><br>
        <main>
            <div class="container">
                <section class="announcements">
                    <center>
                        <h2>Anuncios</h2>
                    </center>
                    <div class="search-container">
                        <form onsubmit="return searchAnnouncements(event)">
                            <input type="text" id="search-input" placeholder="Buscar Anuncio">
                            <img src="img/lupa.png" alt="Buscar" class="search-icon">
                        </form>
                    </div>

                    <div id="announcements">

                    </div>
                </section>

                <div class="icon">
                    <a href="añadiranuncio.php" class="link-button">
                        <button class="add-announcement">Añadir Anuncio</button>
                    </a>
                </div>
            </div>
        </main>
               
               
         
            <script>
            const DEFAULT_ANNOUNCEMENT_IMAGE = 'img/alerta.png';

            async function loadAnnouncements() {
                try {
                    const response = await fetch('http://192.168.1.102:3001/api/anuncios');
                    const anuncios = await response.json();

                    const announcementsContainer = document.getElementById('announcements');

                    if (anuncios.length === 0) {
                        announcementsContainer.innerHTML = "<p>No se encontraron anuncios.</p>";
                        return;
                    }

                    let html = '';
                    anuncios.forEach(anuncio => {

                        const imagenAnuncio = anuncio.img_anuncio || DEFAULT_ANNOUNCEMENT_IMAGE;

                        html += `
                <div class="announcement" id="announcement-${anuncio.idAnuncio}">
                    <img src="${imagenAnuncio}" alt="Imagen del anuncio" style="width:90%; max-width:90px;"><br>
                    <p><b>Anuncio:</b> ${anuncio.titulo}<br>
                        <b>Descripcion:</b> ${anuncio.descripcion}<br>
                        <b>Fecha de Publicación: </b>${anuncio.fechaPublicacion}<br>
                        <b>Hora de Publicación:</b> ${anuncio.horaPublicacion}<br>
                    </p>
                    <button class="delete-button" onclick="deleteAnnouncement(${anuncio.idAnuncio})">Eliminar</button>
                </div>
            `;
                    });

                    announcementsContainer.innerHTML = html;
                } catch (error) {
                    console.error('Error al cargar anuncios:', error);
                    document.getElementById('announcements').innerHTML = "<p>Error al cargar los anuncios.</p>";
                }
            }

            async function searchAnnouncements(event) {
                event.preventDefault();
                const query = document.getElementById('search-input').value.toLowerCase();

                try {
                    const response = await fetch('http://192.168.1.102:3001/api/anuncios');
                    const anuncios = await response.json();

                    const filtered = query ?
                        anuncios.filter(a =>
                            a.titulo.toLowerCase().includes(query) ||
                            a.descripcion.toLowerCase().includes(query)) :
                        anuncios;

                    const announcementsContainer = document.getElementById('announcements');

                    if (filtered.length === 0) {
                        announcementsContainer.innerHTML = "<p>No se encontraron anuncios.</p>";
                        return false;
                    }

                    let html = '';
                    filtered.forEach(anuncio => {

                        const imagenAnuncio = anuncio.img_anuncio || DEFAULT_ANNOUNCEMENT_IMAGE;

                        html += `
                <div class="announcement" id="announcement-${anuncio.idAnuncio}">
                    <img src="${imagenAnuncio}" alt="Imagen del anuncio" style="width:90%; max-width:90px;"><br>
                    <p><b>Anuncio:</b> ${anuncio.titulo}<br>
                        <b>Descripcion:</b> ${anuncio.descripcion}<br>
                        <b>Fecha de Publicación: </b>${anuncio.fechaPublicacion}<br>
                        <b>Hora de Publicación:</b> ${anuncio.horaPublicacion}<br>
                    </p>
                    <button class="delete-button" onclick="deleteAnnouncement(${anuncio.idAnuncio})">Eliminar</button>
                </div>
            `;
                    });

                    announcementsContainer.innerHTML = html;
                } catch (error) {
                    console.error('Error en la búsqueda:', error);
                    document.getElementById('announcements').innerHTML = "<p>Error al buscar anuncios.</p>";
                }

                return false;
            }
            async function deleteAnnouncement(id) {
                if (!confirm('¿Estás seguro de que deseas eliminar este anuncio?')) {
                    return;
                }

                try {
                    const response = await fetch(`http://192.168.1.102:3001/api/elanuncios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Error al eliminar el anuncio');
                    }


                    loadAnnouncements();
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al eliminar el anuncio: ' + error.message);
                }
            }

            document.addEventListener('DOMContentLoaded', loadAnnouncements);
        </script>
            <script>
                const searchInput = document.getElementById('searchInput');
                const announcements = document.querySelectorAll('.announcement');
                searchInput.addEventListener('input', function() {
                    const filter = searchInput.value.toLowerCase();
                    announcements.forEach(function(announcement) {
                        const text = announcement.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            announcement.style.display = 'block';
                        } else {
                            announcement.style.display = 'none';
                        }
                    });
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
                  let currentChat = {
                type: null,
                targetId: null,
                name: null
            };
            let currentUserId = null;

            // Función para obtener cookies
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }

            // Función para cerrar el chat
            function closeChat() {
                document.getElementById('chatContainer').style.display = 'none';
                currentChat = {
                    type: null,
                    targetId: null,
                    name: null
                };
            }

            // Función para enviar mensajes
            async function sendMessage() {
                if (!currentChat.type || !currentChat.targetId) {
                    alert('No hay un chat seleccionado');
                    return;
                }

                const chatInput = document.getElementById('chatInput');
                const message = chatInput.value.trim();

                if (!message) {
                    alert('El mendaje enviado correctamente ');
                    return;
                }

                try {
                    // Mostrar mensaje temporalmente
                    const tempMessage = {
                        id_mensaje: 'temp-' + Date.now(),
                        id_remitente: currentUserId,
                        PrimerNombre: 'Tú',
                        PrimerApellido: '',
                        Roldescripcion: '',
                        contenido: message,
                        fecha_envio: new Date().toISOString()
                    };

                    displayMessages([tempMessage], currentUserId);
                    chatInput.value = '';

                    // Enviar mensaje al servidor
                    const response = await fetch('./chat/chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getCookie('token')
                        },
                        body: JSON.stringify({
                            action: 'send',
                            content: message,
                            chat_type: currentChat.type,
                            receiver_id: currentChat.type === 'privado' ? currentChat.targetId : null,
                            group_chat: currentChat.type === 'grupal' ? currentChat.targetId : null
                        })
                    });

                    const data = await response.json();

                    if (!response.ok || data.status !== 'success') {
                        throw new Error(data.message || 'Error al enviar mensaje');
                    }

                    // Reemplazar mensaje temporal con el real del servidor
                    const tempElement = document.querySelector(`[data-message-id="temp-${tempMessage.id_mensaje.split('-')[1]}"]`);
                    if (tempElement) {
                        tempElement.dataset.messageId = data.message_id;
                    }

                } catch (error) {
                    console.error('Error al enviar mensaje:', error);
                    alert('Error al enviar mensaje: ' + error.message);
                }
            }

            // Función para eliminar un mensaje
            async function deleteMessage(event, messageId) {
                event.stopPropagation();

                if (!confirm('¿Estás seguro de que quieres eliminar este mensaje?')) {
                    return;
                }

                try {
                    const response = await fetch('./chat/chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getCookie('token')
                        },
                        body: JSON.stringify({
                            action: 'delete_message',
                            message_id: messageId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok || data.status !== 'success') {
                        throw new Error(data.message || 'Error al eliminar mensaje');
                    }

                    // Eliminar el mensaje del DOM
                    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                    if (messageElement) {
                        messageElement.remove();
                    }

                } catch (error) {
                    console.error('Error al eliminar mensaje:', error);
                    alert('Error al eliminar mensaje: ' + error.message);
                }
            }
            // Función para cargar usuarios disponibles
            async function loadChatUsers() {
                try {
                    console.log("Cargando usuarios del chat...");
                    const response = await fetch('./chat/chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getCookie('token')
                        },
                        body: JSON.stringify({
                            action: 'get_users'
                        })
                    });

                    console.log("Respuesta del servidor:", response);

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log("Datos recibidos:", data);

                    if (!data || !data.users) {
                        throw new Error('Datos de usuarios no recibidos correctamente');
                    }

                    updateChatMenu(data.users, data.groups || [], data.current_user_id);
                } catch (error) {
                    console.error('Error al cargar usuarios:', error);
                    alert('Error al cargar los contactos: ' + error.message);
                }
            }

            // Función para actualizar el menú de chat
            function updateChatMenu(users, groups, currentUserId) {
                const chatMenu = document.getElementById('chatDropdownMenu');

                if (!chatMenu) {
                    console.error('Menú de chat no encontrado en el DOM');
                    return;
                }

                // Limpiar solo los elementos de contactos (conservar el buscador)
                const contactItems = chatMenu.querySelectorAll('li:not(:first-child)');
                contactItems.forEach(item => item.remove());

                if (users.length === 0) {
                    const noUsersItem = document.createElement('li');
                    noUsersItem.className = 'dropdown-item';
                    noUsersItem.textContent = 'No hay contactos disponibles';
                    chatMenu.appendChild(noUsersItem);
                    return;
                }

                // Función para escapar HTML
                const escapeHtml = (unsafe) => {
                    return unsafe?.toString()
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;") || '';
                };

                // Agregar usuarios
                users.forEach(user => {
                    if (user.id_Registro != currentUserId) {
                        const li = document.createElement('li');
                        li.className = 'chat-contact-item dropdown-item';

                        li.innerHTML = `
                <a href="#" class="chat-item d-flex align-items-center p-2"
                   onclick="openChat('${escapeHtml(user.PrimerNombre)} ${escapeHtml(user.PrimerApellido)}', 
                           ${user.id_Registro}, false)">
                    <img src="./img/usuario.png"  
                         class="rounded-circle me-2" width="30" height="30">
                    <div>
                        <div class="fw-bold">${escapeHtml(user.PrimerNombre)} ${escapeHtml(user.PrimerApellido)}</div>
                        <small class="text-muted">${escapeHtml(user.Roldescripcion)}</small>
                    </div>
                </a>
            `;
                        chatMenu.appendChild(li);
                    }
                });

                // Agregar grupos si existen
                if (groups && groups.length > 0) {
                    const groupHeader = document.createElement('li');
                    groupHeader.className = 'dropdown-header';
                    groupHeader.textContent = 'Grupos';
                    chatMenu.appendChild(groupHeader);

                    groups.forEach(group => {
                        const li = document.createElement('li');
                        li.className = 'chat-contact-item dropdown-item';
                        li.innerHTML = `
                <a href="#" class="chat-item d-flex align-items-center p-2"
                   onclick="openChat('${escapeHtml(group.PrimerNombre)}', 
                           '${escapeHtml(group.id_Registro)}', true)">
                    <img src="img/c.png" alt="${escapeHtml(group.PrimerNombre)}" 
                         class="rounded-circle me-2" width="30" height="30">
                    <div>
                        <div class="fw-bold">${escapeHtml(group.PrimerNombre)}</div>
                        <small class="text-muted">Grupo</small>
                    </div>
                </a>
            `;
                        chatMenu.appendChild(li);
                    });
                }
            }

            // Función para abrir un chat
            function openChat(chatName, targetId = null, isGroup = false) {
                currentChat = {
                    type: isGroup ? 'grupal' : 'privado',
                    targetId: targetId,
                    name: chatName
                };

                const chatContainer = document.getElementById('chatContainer');
                const chatHeader = document.getElementById('chatHeader');

                chatHeader.textContent = chatName;
                chatContainer.style.display = 'flex';
                document.getElementById('chatMessages').innerHTML = '';

                fetchMessages();

                const chatInput = document.getElementById('chatInput');
                chatInput.focus();
                chatInput.onkeypress = function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                };
            }

            // Función para obtener mensajes
            async function fetchMessages() {
                if (!currentChat.type || !currentChat.targetId) return;

                try {
                    const response = await fetch('./chat/chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + getCookie('token')
                        },
                        body: JSON.stringify({
                            action: 'get_messages',
                            chat_type: currentChat.type,
                            target_id: currentChat.targetId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok || data.status !== 'success') {
                        throw new Error(data.message || 'Error al obtener mensajes');
                    }

                    displayMessages(data.messages, data.current_user_id);
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            // Función para mostrar mensajes
            function displayMessages(messages, currentUserId) {
                const chatMessages = document.getElementById('chatMessages');

                // Conservar mensajes temporales
                const tempMessages = Array.from(chatMessages.querySelectorAll('.message.pending'))
                    .map(el => el.outerHTML);

                chatMessages.innerHTML = '';

                // Mostrar mensajes del servidor
                messages.forEach(message => {
                    const isCurrentUser = message.id_remitente == currentUserId;
                    const messageElement = document.createElement('div');
                    messageElement.className = isCurrentUser ? 'message sent' : 'message received';
                    messageElement.dataset.messageId = message.id_mensaje;

                    const messageTime = new Date(message.fecha_envio).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    messageElement.innerHTML = `
            <div class="message-header">
                <span class="sender-name">${message.PrimerNombre} ${message.PrimerApellido} (${message.Roldescripcion})</span>
                <span class="message-time">${messageTime}</span>
                ${isCurrentUser ? '<button class="delete-message-btn" onclick="deleteMessage(event, ' + message.id_mensaje + ')">×</button>' : ''}
            </div>
            <div class="message-content">${message.contenido}</div>
        `;
                    chatMessages.appendChild(messageElement);
                });

                // Restaurar mensajes temporales
                tempMessages.forEach(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    chatMessages.appendChild(tempDiv.firstChild);
                });

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadChatUsers();

                // Configurar evento para el botón de enviar
                document.querySelector('.chat-input button').addEventListener('click', sendMessage);

                // Configurar evento para la tecla Enter
                document.getElementById('chatInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });

                // Actualizar mensajes periódicamente
                setInterval(() => {
                    if (document.getElementById('chatContainer').style.display === 'flex') {
                        fetchMessages();
                    }
                }, 3000);
            });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </main>
    </main>
    </header>
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

</body>

</html>