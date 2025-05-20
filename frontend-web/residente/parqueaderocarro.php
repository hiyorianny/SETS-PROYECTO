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


$query = "SELECT id_Parqueadero, numero_Parqueadero, disponibilidad , uso FROM parqueadero";
try {
    $statement = $base_de_datos->prepare($query);
    $statement->execute();
    $parqueaderos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS -Parqueaderos -carro </title>
    <link rel="stylesheet" href="css/parqueadero.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <div class="topbar">
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
                                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Buscar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
    </header>
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
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
        </div>
        </header>
        <br> <br> <br>
        <div class="container">
            <div id="carro" class="tab-content active">
                <div class="tabs">
                    <a href="parqueaderocarro.php" class="tab-btn active" style="text-decoration: none;">Carro</a>
                    <a href="paromoto.php" class="tab-btn" style="text-decoration: none;">Moto</a>
                </div>
                <section class="pius">
                    <center>
                        <h3>Parqueadero Carro</h3>
                    </center>
                </section>
                <section class="pis">
                    <center>
                        <h3>Parqueadero Zona 1</h3>
                    </center>
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
                                <?php if (!empty($parqueaderos)): ?>
                                    <?php foreach ($parqueaderos as $index => $parqueadero): ?>
                                        <div class="col-6 col-md-2 mb-4 product-card" data-number="<?= htmlspecialchars($parqueadero['numero_Parqueadero']); ?>">
                                            <div class="card text-center">
                                                <h3 class="torres-title"><?= htmlspecialchars($parqueadero['numero_Parqueadero']); ?></h3>
                                                <img src="img/esta.png" alt="" class="product-img">
                                                <button class="btn <?= ($parqueadero['disponibilidad'] === 'SI ESTA DISPONIBLE') ? 'btn-success' : 'btn-danger'; ?>" style="font-size: 13px;">
                                                    <?= htmlspecialchars($parqueadero['disponibilidad']); ?>
                                                </button>
                                                <br>
                                                <h8 style="font-size: 14PX;"><b> DISPONIBLE DESDE O APARTIR DE :</b></h8>
                                                <button class="btn <?= isset($parqueadero['uso']) && $parqueadero['uso'] !== NULL ? 'btn-success' : 'btn-danger'; ?>" style="font-size: 13px;">
                                                    <?= isset($parqueadero['uso']) && $parqueadero['uso'] !== NULL ? date('Y-m-d H:i:s', strtotime($parqueadero['uso'])) : ''; ?>
                                                </button>
                                            </div>
                                        </div>
                                        <?php if (($index + 1) % 5 == 0): ?>
                            </div>
                            <div class="row">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </center>
                </div>
            </div>
            <p>
                <center>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a href="horariocarro.php" type="button" class="btn btn-warning">Ver Solicitudes para visitantes</a>

                    </div>
                </center>
        </div>
        <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size: 30px;">Volver</a>

    
        
        <br>
        </div>
        <br>
        </div>
        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const cards = document.querySelectorAll('.product-card');

                cards.forEach(card => {
                    const number = card.getAttribute('data-number').toLowerCase();
                    if (number.includes(query)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>