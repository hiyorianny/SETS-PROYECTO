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


if ($idRol != 3333) {
    header("Location: http://localhost/sets/error.php");
    exit();
}

include_once "conexion.php";


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Torre, Piso y Apartamento</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/i.css?v=<?php echo (rand()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:6rem_4rem]">
            <div class="absolute bottom-0 left-0 right-0 top-0 bg-[radial-gradient(circle_800px_at_100%_200px,#d5c5ff,transparent)]"></div>
        </div>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
                </a><button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color: white;">
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
                                            <center><a href="Perfil.php">Editar datos</a></center>
                                        </li>
                                        <li>
                                            <center> <a href="../../MODEL/backend/logout.php">Cerrar sesión</a></center>
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
                            <input class="form-control me-2" type="search" placeholder="buscar" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div id="chatContainer" class="chat-container">
        <div class="chat-header">
            <h2 id="chatHeader">Chat</h2>
            <button class="close-btn" onclick="closeChat()">×</button>
        </div>
        <div class="chat-messages" id="chatMessages">
        </div>
        <div class="chat-input">
            <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>
    <br><br><br>
    <br><br><br>
    <main>
        <center>
        <div class="alert alert-success" role="alert" style="width: 383px;">
            <h2>Agregar Torre, Piso y Apartamento</h2>
        </div>
        </center>

        <center>
                <div id="infoSeleccionada" class="mt-4">
                    <h3><b>Información Seleccionada:</b></h3>
                    <p><strong>Torre:</strong> <span id="torreSeleccionada"></span></p>
                    <p><strong>Piso:</strong> <span id="pisoSeleccionado"></span></p>
                    <p><strong>Apartamento:</strong> <span id="apartamentoSeleccionado"></span></p>
                </div>
            </center>
        <div class="container">
            <form id="seleccionForm">
                <label for="torre">Torre:</label>
                <select name="torre" id="torre">

                    <?php
                    $stmt = $base_de_datos->query("SELECT DISTINCT torre FROM apartamento LIMIT 10");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['torre']}'>{$row['torre']}</option>";
                    }
                    ?>
                </select>

                <label for="piso">Piso:</label>
                <select name="piso" id="piso">

                    <?php
                    $stmt = $base_de_datos->query("SELECT pisos  FROM apartamento  ORDER BY CAST(SUBSTRING(pisos, 1, 2) AS UNSIGNED) ");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['pisos']}'>{$row['pisos']}</option>";
                    }
                    ?>
                </select>

                <label for="apartamento">Apartamento:</label>
                <select name="apartamento" id="apartamento">

                    <?php
                    $stmt = $base_de_datos->query("SELECT numApartamento FROM apartamento");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['numApartamento']}'>{$row['numApartamento']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <center>
                    <input type="button" class="btn btn-success" value="Guardar y Mostrar Información" onclick="guardarSeleccion(); mostrarInformacion();">
                </center>
            </form>

         
        </div>
    </main>
    <br>
    <br>
    <a href="perfil.php" type="button" class="btn btn-danger btn-lg">Volver</a>

    <script>
        function guardarSeleccion() {
            const torre = document.getElementById('torre').value;
            const piso = document.getElementById('piso').value;
            const apartamento = document.getElementById('apartamento').value;

            // Guardar en LocalStorage
            localStorage.setItem('torreSeleccionada', torre);
            localStorage.setItem('pisoSeleccionado', piso);
            localStorage.setItem('apartamentoSeleccionado', apartamento);

            alert('informacion guardada correctamente.');
        }

        function mostrarInformacion() {
            const torre = document.getElementById('torre').value;
            const piso = document.getElementById('piso').value;
            const apartamento = document.getElementById('apartamento').value;

            document.getElementById('torreSeleccionada').textContent = torre;
            document.getElementById('pisoSeleccionado').textContent = piso;
            document.getElementById('apartamentoSeleccionado').textContent = apartamento;

            document.getElementById('infoSeleccionada').style.display = 'block';
        }

        function cargarSeleccion() {
            const torre = localStorage.getItem('torreSeleccionada');
            const piso = localStorage.getItem('pisoSeleccionado');
            const apartamento = localStorage.getItem('apartamentoSeleccionado');

            if (torre && piso && apartamento) {
                document.getElementById('torre').value = torre;
                document.getElementById('piso').value = piso;
                document.getElementById('apartamento').value = apartamento;

                mostrarInformacion();
            }
        }

       
        window.onload = cargarSeleccion;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>