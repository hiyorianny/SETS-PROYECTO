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

// Para respuesta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['responder'])) {
    $idcita  = $_POST['idcita'];
    $respuesta = $_POST['respuesta'];

    // Ya al tener una respuesta y actualizar
    $sql = "UPDATE cita SET respuesta = :respuesta, estado = 'respondida' WHERE idcita  = :idcita";
    $stmt = $base_de_datos->prepare($sql);

    if ($stmt->execute(['respuesta' => $respuesta, 'idcita' => $idcita])) {
    } else {
        echo "Error al enviar la respuesta.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $idcita  = $_POST['delete_id'];

    // Borrar una cita
    $sql = "DELETE FROM cita WHERE idcita  = :idcita";
    $stmt = $base_de_datos->prepare($sql);

    if ($stmt->execute(['idcita' => $idcita])) {
    } else {
        echo "Error al eliminar la cita.";
    }
}

// Tener las citas
$sql = "SELECT * FROM cita";
$stmt = $base_de_datos->query($sql);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    </main>
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
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cita['tipocita']); ?></td>
                                <td><?php echo htmlspecialchars($cita['fechacita']); ?></td>
                                <td><?php echo htmlspecialchars($cita['horacita']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                <td><?php echo htmlspecialchars($cita['respuesta']); ?></td>
                                <td>

                                    <form action="" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $cita['idcita']; ?>">
                                        <button class="btn btn-danger mt-3" type="submit" name="delete">Eliminar</button>
                                        <link rel="stylesheet" href="administrar.css">
                                    </form>
                                </td>
                                <td>
                                    <?php if ($cita['estado'] == 'pendiente'): ?>
                                        <form action="" method="post">
                                            <input type="hidden" name="idcita" value="<?php echo $cita['idcita']; ?>">
                                            <textarea name="respuesta" required placeholder="Escribe tu respuesta aquí"></textarea>
                                            <button class="btn btn-secondary" type="submit" name="responder">Enviar Respuesta</button>
                                        </form>
                                    <?php else: ?>
                                        <span>Respondida</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
     <br> <br>  <br>   <br>  <br>  <br>  <br>  <br>  <br>  <br>  <br>  <br>
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