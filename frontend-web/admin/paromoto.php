<?php
require __DIR__ . '/../../Backend/auth/controller/admin.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - PMoto</title>
    <link rel="stylesheet" href="css/moto.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <div id="carro" class="tab-content active">
            <div class="tabs">
                <a href="parqueaderocarro.php" class="tab-btn active" onclick="showTab('carro')"
                    style="text-decoration: none;">Carro</a>
                <a href="paromoto.php" class="tab-btn" onclick="showTab('moto')"
                    style="text-decoration: none;">Moto</a>
            </div>
            <section class="pius">
                <h3 style="text-align: center;">Parqueadero MOTO</h3>
            </section>
            <section class="pis">
                <h3 style="text-align: center;">Parqueadero Zona 1</h3>
            </section>
            <div class="search-bar-container">
                <div class="barra">
                    <input type="text" id="searchInput" placeholder="Buscar parqueadero...">
                    <ion-icon name="search-outline"></ion-icon>
                </div>
                <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
                <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
            </div>
            <div class="torress">
                <center>
                    <div class="container">
                        <div class="row" id="parqueaderosContainer">

                            <div class="text-center">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p>Cargando datos de parqueaderos...</p>
                            </div>
                        </div>
                    </div>
                </center>

            </div>
        </div>
        <br>
        <center><a href="hoariomoto.php" class="small-btn" style="text-decoration: none;">Ver Horario Parqueadero Visitante</a></center>

    </div>


    <br>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="inicioprincipal.php" class="btn btn-outline-success" style=" font-size:30px;">
            <center>VOLVER</center>
        </a>
    </div>
    </main>
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

        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab-btn[onclick="showTab('${tabId}')"]`).classList.add('active');
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
    <script>
        async function cargarParqueaderos() {
            try {
                const response = await fetch('http://192.168.1.102:3001/api/parqueaderos');
                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }
                const parqueaderos = await response.json();

                const container = document.getElementById('parqueaderosContainer');

                if (parqueaderos.length === 0) {
                    container.innerHTML = `
                        <div class="alert alert-warning">
                            No se encontraron parqueaderos registrados
                        </div>
                    `;
                    return;
                }

                let html = '';
                parqueaderos.forEach((parqueadero, index) => {
                    html += `
                        <div class="col-6 col-md-2 mb-4 product-card" data-number="${parqueadero.numero_Parqueadero}">
                            <div class="card text-center">
                                <h3 class="torres-title">${parqueadero.id_parqueadero}</h3>
                                <img src="img/moto.png" alt="" class="product-img">
                                <button class="btn ${parqueadero.disponibilidad === 'SI ESTA DISPONIBLE' ? 'btn-success' : 'btn-danger'}" style="font-size: 13px;">
                                    ${parqueadero.disponibilidad}
                                </button>
                                <br>
                                <h8 style="font-size: 14PX;"><b> DISPONIBLE DESDE O APARTIR DE :</b></h8>
                                <button class="btn ${parqueadero.uso ? 'btn-success' : 'btn-danger'}" style="font-size: 13px;">
                                    ${parqueadero.uso ? new Date(parqueadero.uso).toLocaleString() : ''}
                                </button>
                            </div>
                        </div>
                    `;


                    if ((index + 1) % 5 === 0) {
                        html += `</div><div class="row">`;
                    }
                });

                container.innerHTML = html;


                document.getElementById('searchInput').addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const cards = document.querySelectorAll('.product-card');

                    cards.forEach(card => {
                        const number = card.getAttribute('data-number').toLowerCase();
                        card.style.display = number.includes(query) ? 'block' : 'none';
                    });
                });

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('parqueaderosContainer').innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar los parqueaderos: ${error.message}
                        <button onclick="cargarParqueaderos()" class="btn btn-sm btn-warning mt-2">Reintentar</button>
                    </div>
                `;
            }
        }


        document.addEventListener('DOMContentLoaded', cargarParqueaderos);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
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

</html>