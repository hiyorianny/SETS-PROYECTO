<?php
require __DIR__ . '/../../Backend/auth/controller/guarda.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS -  Informes </title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/principal.css?v=<?php echo (rand()); ?>">
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
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="text-align: center;"><b>SETS</b></h5>
                        </center>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <form class="d-flex mt-3" role="search">
                                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Buscar</button>
                            </form>
                            <br>
                            <br>

                            <div class="offcanvas-header">
                                <img src="img/pagina-de-inicio.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./inicioprincipal.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Inicio</b></a>
                                </center>
                            </div>
                            <br>
                            <br>
        
                            </center>
                            <div class="offcanvas-header">
                                <img src="img/notificacion.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="notificaciones.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Notificaciones</b></a>
                                </center>
                            </div>

                            <div class="offcanvas-header">
                                <img src="img/ayudar (1).png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./ayuda.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Ayuda</b></a>
                                </center>
                            </div>
                            <center>

                        </ul>

                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <br><br>
  
       
        <script>
            const searchEventInput = document.getElementById('searchEventInput');
            const events = document.querySelectorAll('.event');

            searchEventInput.addEventListener('input', function() {
                const filter = searchEventInput.value.toLowerCase();

                events.forEach(function(event) {
                    const text = event.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        event.style.display = 'block';
                    } else {
                        event.style.display = 'none';
                    }
                });
            });
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </main>
    </main>
    </header>
</body>

</html>