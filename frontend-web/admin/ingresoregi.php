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




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
  $idIngreso_Peatonal = $_POST['delete_idIngreso_Peatonal'];


  $sql = "DELETE FROM  ingreso_peatonal WHERE idIngreso_Peatonal  = :idIngreso_Peatonal";
  $stmt = $base_de_datos->prepare($sql);

  if ($stmt->execute(['idIngreso_Peatonal' => $idIngreso_Peatonal])) {
  } else {
    echo "Error al eliminar el ingreso.";
  }
}
$sql = "SELECT * FROM  ingreso_peatonal";
$stmt = $base_de_datos->query($sql);
$Ingreso_Peatonal = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sets - Ingreso Peatonal</title>
  <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
  <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
</head>
</head>

<body>
  <header>
    <div class="topbar">
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

              </ul>

              <form class="d-flex mt-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
              </form>
            </div>
          </div>
        </div>
      </nav>
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
  </header>
  <main>
    <br> <br> <br>
    <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;"><b>Ingreso Peatonal</b> </div>
    <div class="container">
      <div class="row">
        <center>
          <div class="col-sm-12 col-md-8 col-lg-8 mt-5">
            <center>
              <h2><b>Panel de Ingresos</b></h2>
            </center>
            <br>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">idIngreso</th>
                  <th scope="col">Nombre de Persona de Ingreso</th>
                  <th scope="col">fecha y Hora</th>
                  <th scope="col">Tipo y Numero de Documento</th>
                  <th scope="col">Tipo ingreso</th>
                  <th scope="col">Acciones</th>

                </tr>
              </thead>
              <tbody>
                <?php foreach ($Ingreso_Peatonal as $Ingreso_Peatonal): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($Ingreso_Peatonal['idIngreso_Peatonal']); ?></td>
                    <td><?php echo htmlspecialchars($Ingreso_Peatonal['personasIngreso']); ?></td>
                    <td><?php echo htmlspecialchars($Ingreso_Peatonal['horaFecha']); ?></td>
                    <td><?php echo htmlspecialchars($Ingreso_Peatonal['documento']); ?></td>
                    <td><?php echo htmlspecialchars($Ingreso_Peatonal['tipo_ingreso']); ?></td>
                    <td>
                      <form action="" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar ?');">
                        <input type="hidden" name="delete_idIngreso_Peatonal" value="<?php echo $Ingreso_Peatonal['idIngreso_Peatonal']; ?>">
                        <button class="btn btn-danger mt-3 " type="submit" name="delete">Eliminar</button>
                      </form>
                    </td>

                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <br>
          <div class="container mt-5">
            <a href="torres.php" class="btn btn-success">Volver</a>
          </div>
      </div>
      </center>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<br>
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

</html>

</body>

</html>