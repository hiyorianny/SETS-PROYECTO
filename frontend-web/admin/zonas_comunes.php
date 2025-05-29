<?php
require __DIR__ . '/../../Backend/auth/controller/admin.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Zonas_Comunes</title>
    <link rel="stylesheet" href="css/zonas_comunes.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
            <img src="img/ajustes.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
            <b style="font-size: 25px;color:aliceblue"> ADMIN - <?php echo htmlspecialchars($Usuario); ?>  </b></a>
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

                    </div>
                </div>
            </div>
        </nav>

    </header>
    <br><br>
    
    <main>
        <br>
        <br>
        <section class="zones-section container mt-5">
            <h1 class="title text-center mb-5"><b>Zonas Comunes</b></h1>
            <div class="row" id="zonasContainer">
                <!-- Las zonas se cargarán aquí dinámicamente -->
            </div>
        </section>
        <a href="inicioprincipal.php" class="btn btn-outline-danger  btn-lg">Volver</a>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function getAuthToken() {
            return localStorage.getItem('authToken');
        }

        async function loadZonasComunes() {
            try {
                const response = await fetch('http://192.168.1.102:3001/api/zonas-comunes', {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Error al cargar las zonas comunes');
                }
                
                const zonas = await response.json();
                displayZonas(zonas);
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar las zonas comunes');
            }
        }


        function displayZonas(zonas) {
            const container = document.getElementById('zonasContainer');
            container.innerHTML = '';
            
            if (zonas.length === 0) {
                container.innerHTML = '<p>No hay zonas comunes disponibles</p>';
                return;
            }
            
            zonas.forEach(zona => {
                const zonaCol = document.createElement('div');
                zonaCol.className = 'col-12 col-md-6';
                
                const pagina = getPaginaSolicitud(zona.idZona);
                
                zonaCol.innerHTML = `
                    <article class="zone">
                        <button class="zone-type-btn">
                            <h3>${escapeHtml(zona.idZona)}</h3>
                        </button>
                        <div class="video-wrapper">
                            <video src="${escapeHtml(zona.url_videos)}" autoplay loop muted></video>
                        </div>
                        <h2 class="zone-description">${escapeHtml(zona.descripcion)}</h2>
                        <h6>Costo de Alquiler</h6>
                        <h2 class="zone-description">${escapeHtml(zona.costo_alquiler)}</h2>
                        <a href="${escapeHtml(pagina)}?id=${escapeHtml(zona.idZona)}" class="btn btn-outline-success">
                            Ver Horario Disponible
                        </a><br>
                        <a class="btn btn-success" href="./actualizarzona.php?idZona=${escapeHtml(zona.idZona)}">
                            <center>
                                <h3 style="font-size: 15px;"><b>Editar</b></h3>
                            </center>
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete(${escapeHtml(zona.idZona)})">
                            Eliminar
                        </button>
                    </article>
                    <br><br>
                `;
                
                container.appendChild(zonaCol);
            });
        }


        function getPaginaSolicitud(idZona) {
        const zonasMap = {
            1: 'solicitarfutbol.php',
            2: 'solicitarbbq.php',
            3: 'solicitarsalon.php',
            4: 'solicitarvoley.php',
            5: 'solicitargym.php'
        };
        return zonasMap[idZona] || '#';
    }

        // Función para confirmar eliminación de zona
        async function confirmDelete(idZona) {
            if (confirm('¿Estás seguro de que deseas eliminar esta zona común?')) {
                try {
                    const response = await fetch(`http://192.168.1.102:3001/api/zonas-comunes/${idZona}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${getAuthToken()}`
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Error al eliminar la zona');
                    }
                    
                    alert('Zona eliminada correctamente');
                    loadZonasComunes(); // Recargar la lista
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al eliminar la zona');
                }
            }
        }

        // Función para escapar HTML (seguridad)
        function escapeHtml(unsafe) {
            return unsafe
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Función para cargar información del usuario
        async function loadUserInfo() {
            try {
                const response = await fetch('http://192.168.1.102:3001/api/auth/user', {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Error al cargar información del usuario');
                }
                
                const user = await response.json();
                document.getElementById('adminUsername').textContent = user.nombre || 'Administrador';
            } catch (error) {
                console.error('Error:', error);
            }
        }

  

        // Cargar datos cuando la página esté lista
        document.addEventListener('DOMContentLoaded', function() {
            loadUserInfo();
            loadZonasComunes();
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