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

$sql = "SELECT * FROM anuncio";
$result = $base_de_datos->query($sql);

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $isEvent = strpos($row["titulo"], "Evento") !== false;
    }
}
$query = isset($_GET['query']) ? $_GET['query'] : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS-ADMI</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/principal.css?v=<?php echo (rand()); ?>">
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
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="img/hablando.png" alt="Logo" width="30" height="44" class="d-inline-block align-text-top">
                                        <b style="font-size: 20px;"> CHAT</b>

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

                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="torres.php" class="link-button">
                                <img src="img/casa.png" alt="Torres" class="medium-img">
                                <button class="add-announcement">Torre</button>
                            </a>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="parqueaderocarro.php" class="link-button">
                                <img src="img/coche.png" alt="Parqueadero" class="medium-img">
                                <button class="add-announcement">Parqueadero</button>
                            </a>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="zonas_comunes.php" class="link-button">
                                <img src="img/campo.png" alt="Zonas Comunes" class="medium-img">
                                <button class="add-announcement">Zonas Comunes</button>
                            </a>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="manualconvivencia.php" class="link-button">
                                <img src="img/instrucciones.png" alt="Manual de convivencia" class="medium-img">
                                <center><button class="add-announcement">Manual de convivencia</button></center>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="datos_usuario.php" class="link-button">
                                <img src="img/inf.png" alt="Datos Usuarios" class="medium-img">
                                <button class="add-announcement">Datos Usuarios</button>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="citas.php" class="link-button">
                                <img src="img/citas.png" alt="Citas con amd" class="medium-img">
                                <button class="add-announcement">Citas</button>
                            </a>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="contactanos.php" class="link-button">
                                <img src="img/formulario-de-inscripcion.png" alt="Datos Usuarios" class="medium-img">
                                <button class="add-announcement">Contàctanos</button>
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="icon">
                            <a href="pagos.php" class="link-button">
                                <img src="img/social.png" alt="Citas con amd" class="medium-img">
                                <button class="add-announcement">Pagos</button>
                            </a>
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
        <main>
            <div class="container">
                <section class="announcements">
                    <center>
                        <h2>Anuncios</h2>
                    </center>
                    <div class="search-container">
                        <form onsubmit="return searchAnnouncements();">
                            <input type="text" id="search-input" placeholder="Buscar Anuncio">
                            <img src="img/lupa.png" alt="Buscar" class="search-icon">
                        </form>
                    </div>
                    <div id="announcements">
                        <?php
                        $sql = "SELECT * FROM anuncio";
                        $result = $base_de_datos->query($sql);
                        if ($result->rowCount() > 0) {
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <div class="announcement" id="announcement-<?= htmlspecialchars($row['titulo']); ?>">
                                    <img src="<?= htmlspecialchars($row['img_anuncio']); ?>" alt="Imagen" style="width:90%; max-width:90px;"><br>
                                    <p><b>Anuncio:</b> <?= htmlspecialchars($row["titulo"]); ?><br>
                                        <b>Descripcion:</b> <?= htmlspecialchars($row["descripcion"]); ?><br>
                                        <b>Fecha Publicación: </b><?= htmlspecialchars($row["fechaPublicacion"]); ?><br>
                                        <b>Hora de Publicación:</b> <?= htmlspecialchars($row["horaPublicacion"]); ?><br>
                                    </p>
                                    <button class="delete-button" onclick="deleteAnnouncement('<?= htmlspecialchars($row['titulo']); ?>')">Eliminar</button>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No se encontraron anuncios.</p>";
                        }
                        ?>
                    </div>
                </section>
                <script>
                    function deleteAnnouncement(titulo) {
                        if (confirm("¿Está seguro de que desea eliminar este anuncio?")) {

                            fetch('../../CONTROLLER/anuncioadmin.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: new URLSearchParams({
                                        'titulo': titulo,
                                        'accion': 'eliminar'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {

                                        document.getElementById('announcement-' + titulo).remove();
                                        alert(data.message);
                                    } else {
                                        alert(data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al eliminar el anuncio:', error);
                                    alert("Hubo un error al eliminar el anuncio.");
                                });
                        }
                    }
                </script>
                <div class="icon">
                    <a href="añadiranuncio.php" class="link-button">
                        <button class="add-announcement">Añadir Anuncio</button>
                    </a>
                </div>
            </div>
            <script>
                function searchAnnouncements() {
                    var query = document.getElementById('search-input').value;
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', './buscador.php?query=' + encodeURIComponent(query), true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            document.getElementById('announcements').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error en la búsqueda:', xhr.statusText);
                        }
                    };
                    xhr.send();
                    return false;
                }
            </script>
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
                // Variables globales para el estado del chat
                let currentChat = {
                    type: null,
                    targetId: null,
                    name: null
                };

                // Función para obtener cookies
                function getCookie(name) {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; ${name}=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
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
                    const messageInput = document.getElementById('chatInput');
                    const chatMessages = document.getElementById('chatMessages');

                    if (!currentChat.type || !currentChat.targetId) {
                        alert('Por favor, selecciona un chat primero');
                        return;
                    }

                    const messageText = messageInput.value.trim();
                    if (messageText === '') {
                        alert('Mensaje Enviado Correctamente');
                        return;
                    }

                    // Mostrar mensaje localmente inmediatamente
                    const tempMessageId = 'temp-' + Date.now();
                    const tempMessage = document.createElement('div');
                    tempMessage.id = tempMessageId;
                    tempMessage.className = 'message sent pending';
                    tempMessage.innerHTML = `
        <div class="message-header">
            <span class="sender-name">Tú</span>
            <span class="message-time">Enviando...</span>
        </div>
        <div class="message-content">${messageText}</div>
    `;
                    chatMessages.appendChild(tempMessage);
                    chatMessages.scrollTop = chatMessages.scrollHeight;

                    // Limpiar el input
                    messageInput.value = '';

                    try {
                        const requestData = {
                            action: 'send',
                            content: messageText,
                            chat_type: currentChat.type,
                            receiver_id: currentChat.type === 'privado' ? currentChat.targetId : null,
                            group_chat: currentChat.type === 'grupal' ? currentChat.targetId : null
                        };

                        const response = await fetch('./chat/chat.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + getCookie('token')
                            },
                            body: JSON.stringify(requestData)
                        });

                        const data = await response.json();

                        if (!response.ok || data.status !== 'success') {
                            throw new Error(data.message || 'Error al enviar el mensaje');
                        }

                        // Actualizar el mensaje temporal con la información real
                        const messageTime = new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        tempMessage.innerHTML = `
            <div class="message-header">
                <span class="sender-name">Tú</span>
                <span class="message-time">${messageTime}</span>
            </div>
            <div class="message-content">${messageText}</div>
        `;
                        tempMessage.classList.remove('pending');

                        // Forzar actualización de mensajes
                        setTimeout(fetchMessages, 500);
                    } catch (error) {
                        console.error('Error:', error);
                        tempMessage.innerHTML += `<div class="message-error">Error: ${error.message}</div>`;
                        alert('Error al enviar el mensaje: ' + error.message);
                    }
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
                // Función para mostrar mensajes
                function displayMessages(messages, currentUserId) {
                    const chatMessages = document.getElementById('chatMessages');

                    // Conservar los mensajes temporales (los que están siendo enviados)
                    const tempMessages = Array.from(chatMessages.querySelectorAll('.message.pending'))
                        .map(el => el.outerHTML);

                    chatMessages.innerHTML = '';

                    // Mostrar todos los mensajes del servidor
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
                ${isCurrentUser || <?php echo $idRol; ?> == 1111 ? 
                    '<button class="delete-message-btn" onclick="deleteMessage(event, ' + message.id_mensaje + ')">×</button>' : ''}
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

                // Función para eliminar mensajes (actualizada)
                async function deleteMessage(event, messageId) {
                    event.stopPropagation();

                    if (!confirm('¿Estás seguro de que quieres eliminar este mensaje?\nSolo desaparecerá de tu vista.')) {
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
                            throw new Error(data.message || 'Error al eliminar el mensaje');
                        }

                        // Eliminar el mensaje del DOM sin recargar todos los mensajes
                        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                        if (messageElement) {
                            messageElement.style.opacity = '0.5';
                            messageElement.style.textDecoration = 'line-through';
                            messageElement.querySelector('.delete-message-btn').remove();

                            // Opcional: eliminar completamente el mensaje después de una animación
                            setTimeout(() => {
                                messageElement.remove();
                            }, 500);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al eliminar el mensaje: ' + error.message);
                    }
                }

                // Función para cargar usuarios disponibles
                async function loadChatUsers() {
                    try {
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

                        const data = await response.json();

                        if (!response.ok || data.status !== 'success') {
                            throw new Error(data.message || 'Error al cargar usuarios');
                        }

                        updateChatMenu(data.users, data.groups, data.current_user_id);
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }

                // Función para actualizar el menú de chat
                function updateChatMenu(users, groups, currentUserId) {
                    const chatMenu = document.getElementById('chatDropdownMenu');

                    if (!chatMenu) {
                        console.error('No se encontró el menú de chat en el DOM');
                        return;
                    }

                    // Limpiar solo elementos dinámicos (conservar elementos estáticos)
                    const dynamicItems = Array.from(chatMenu.querySelectorAll('li:not(.static)'));
                    dynamicItems.forEach(item => item.remove());

                    // Agregar barra de búsqueda
                    const searchItem = document.createElement('li');
                    searchItem.innerHTML = `
        <div class="chat-search-container p-2">
            <input type="text" class="form-control form-control-sm chat-search-input" 
                   placeholder="Buscar contacto..." oninput="filterChatContacts()">
        </div>
    `;
                    chatMenu.appendChild(searchItem);

                    // Agregar usuarios disponibles
                    users.forEach(user => {
                        if (user.id_Registro != currentUserId) {
                            const li = document.createElement('li');
                            li.className = 'chat-contact-item';

                            // Usar imagen de perfil si existe, sino una por defecto
                            const userImage = user.imagenPerfil ?
                                `uploads/${user.imagenPerfil}` : // Asumiendo que las imágenes están en una carpeta uploads
                                'img/c.jpg'; // Imagen por defecto

                            li.innerHTML = `
                <a href="#" class="chat-item d-flex align-items-center" 
                   onclick="openChat('${user.PrimerNombre} ${user.PrimerApellido}', ${user.id_Registro}, false)">
                   
                    <div>
                        <div class="fw-bold">${user.PrimerNombre} ${user.PrimerApellido}</div>
                        <small class="text-muted">${user.Roldescripcion}</small>
                    </div>
                </a>
            `;
                            chatMenu.appendChild(li);
                        }
                    })

                    // Agregar grupos
                    if (groups && groups.length > 0) {
                        const groupHeader = document.createElement('li');
                        groupHeader.className = 'dropdown-header';
                        groupHeader.textContent = 'Grupos';
                        chatMenu.appendChild(groupHeader);

                        groups.forEach(group => {
                            const li = document.createElement('li');
                            li.className = 'chat-contact-item';
                            li.innerHTML = `
                <a href="#" class="chat-item d-flex align-items-center" 
                   onclick="openChat('${group.PrimerNombre}', '${group.id_Registro}', true)">
                    <img src="img/c.png" alt="${group.PrimerNombre}" class="rounded-circle me-2" width="30" height="30">
                    <div>
                        <div class="fw-bold">${group.PrimerNombre}</div>
                        <small class="text-muted">Grupo</small>
                    </div>
                </a>
            `;
                            chatMenu.appendChild(li);
                        });
                    }
                }

                // Función para filtrar contactos en el chat
                function filterChatContacts() {
                    const searchInput = document.querySelector('.chat-search-input');
                    const filter = searchInput.value.toLowerCase();
                    const items = document.querySelectorAll('.chat-contact-item');

                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }

                // Inicialización del chat al cargar la página
                document.addEventListener('DOMContentLoaded', function() {
                    loadChatUsers();

                    // Configurar evento para el botón de enviar
                    document.querySelector('.chat-input button').addEventListener('click', sendMessage);

                    // Actualizar mensajes periódicamente cada 3 segundos si el chat está abierto
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
    </header>

</body>


</html>