<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Ayuda</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/manualconvivencia.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
        <br><br>
        <br><br>
      <center>  <h1 class="responsive-title"><b>Manual de Ayuda!</b></h1></center>
        <br>
        <center>
            <div class="page-container">
                <section class="document-section">

                    <div class="document-content">
                        <iframe src="../../Documentacion-SETS/sexto trimestre/MANUALES/Manuales de usuario/manual_cliente.pdf" style="width: 100%; height: 1800px;" frameborder="0"></iframe>
                    </div>
                </section>
            </div>
        </center>
    </main>
    <a href="inicioprincipal.php" class="btn btn-outline-success btn-lg" style="font-size: 40px;">VOLVER</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>