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
    $idPagos = $_POST['delete_idPagos'];

    // Borrar un pago
    $sql = "DELETE FROM pagos WHERE idPagos = :idPagos";
    $stmt = $base_de_datos->prepare($sql);

    if ($stmt->execute(['idPagos' => $idPagos])) {
        echo "<script>
                alert('Pago eliminado con éxito.');
                window.location.href = 'pagos.php'; // Redirige a la página principal
              </script>";
    } else {
        echo "Error al eliminar el pago.";
    }
}
$sql = "SELECT * FROM pagos";
$stmt = $base_de_datos->query($sql);
$pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Insertar Pagos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/ajustes.png" alt="Logo" width="80" height="84" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
                <b style="font-size: 40px;color:aliceblue"> ADMIN - <?php echo htmlspecialchars($Usuario); ?> </b></a>
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
                <input type="text" id="chatInput" style="font-size: 14px;" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>

    </main>
    <main>
        <br> <br> <br>
        <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;">Insertar Pagos</div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-4 mt-5">
                    <form action="../../CONTROLLER/money.php" method="post">
                        <fieldset>
                            <center>
                                <legend><b>Insertar Pago</b></legend>
                            </center>
                            <div class="mb-3">
                                <label for="pagoPor" class="form-label">Pago Por:</label>
                                <input type="text" class="form-control" id="pagoPor" name="pagoPor" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="mediopago" class="form-label">Medio de Pago:</label>
                                <select class="form-control" id="mediopago" name="mediopago" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Tarjeta">Tarjeta</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="apart" class="form-label">Apartamento:</label>
                                <input type="text" class="form-control" id="apart" name="apart" required>
                            </div>
                            <div class="mb-3">
                                <label for="fechaPago" class="form-label">Fecha de Pago:</label>
                                <input type="date" class="form-control" id="fechaPago" name="fechaPago" required>
                            </div>
                            <div class="mb-3">
                                <label for="referenciaPago" class="form-label">Referencia de Pago:</label>
                                <input type="text" class="form-control" id="referenciaPago" name="referenciaPago">
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado:</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Pagado">Pagado</option>
                                    <option value="Vencido">Vencido</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-8 mt-5">
                    <center>
                        <h2>Panel de Pagos</h2>
                    </center>
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">idPagos</th>
                                <th scope="col">pagoPor</th>
                                <th scope="col">cantidad</th>
                                <th scope="col">mediopago</th>
                                <th scope="col">apart</th>
                                <th scope="col">fechaPago</th>
                                <th scope="col">referenciaPago</th>
                                <th scope="col">estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagos as $pago): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pago['idPagos']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['pagoPor']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['mediopago']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['apart']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['fechaPago']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['referenciaPago']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['estado']); ?></td>
                                    <td>
                                        <form action="../../CONTROLLER/money.php" method="post" style="display: inline;">
                                            <input type="hidden" name="idPagos" value="<?php echo $pago['idPagos']; ?>">
                                            <select name="nuevoEstado" class="form-select" onchange="this.form.submit()">
                                                <option value="Pendiente" <?php echo ($pago['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="Pagado" <?php echo ($pago['estado'] == 'Pagado') ? 'selected' : ''; ?>>Pagado</option>
                                                <option value="Vencido" <?php echo ($pago['estado'] == 'Vencido') ? 'selected' : ''; ?>>Vencido</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pago?');">
                                            <input type="hidden" name="delete_idPagos" value="<?php echo $pago['idPagos']; ?>">
                                            <button class="btn btn-danger" type="submit" name="delete">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <a href="inicioprincipal.php" class="btn btn-success">Volver</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script type="text/javascript" src="JAVA/main.js"></script>
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
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const fechaPagoInput = document.getElementById('fechaPago');

                // Establecer fecha mínima como hoy
                const today = new Date();
                const dd = String(today.getDate()).padStart(2, '0');
                const mm = String(today.getMonth() + 1).padStart(2, '0'); 
                const yyyy = today.getFullYear();
                const fechaHoy = yyyy + '-' + mm + '-' + dd;
                fechaPagoInput.setAttribute('min', fechaHoy);

                // Validación al enviar el formulario
                form.addEventListener('submit', function(e) {
                    const fechaSeleccionada = new Date(fechaPagoInput.value);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0); // Resetear horas para comparar solo fechas

                    if (fechaSeleccionada < hoy) {
                        alert('No puedes registrar pagos con fecha en el pasado');
                        e.preventDefault();
                        return false;
                    }

                    // Validar que la cantidad sea positiva
                    const cantidadInput = document.getElementById('cantidad');
                    if (parseFloat(cantidadInput.value) <= 0) {
                        alert('La cantidad debe ser mayor a cero');
                        e.preventDefault();
                        return false;
                    }

                    return true;
                });

                // Validación en tiempo real para la fecha
                fechaPagoInput.addEventListener('change', function() {
                    const fechaSeleccionada = new Date(this.value);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);

                    if (fechaSeleccionada < hoy) {
                        alert('No puedes seleccionar una fecha en el pasado');
                        this.value = fechaHoy;
                    }
                });

                // Validación en tiempo real para la cantidad
                document.getElementById('cantidad').addEventListener('change', function() {
                    if (parseFloat(this.value) <= 0) {
                        alert('La cantidad debe ser mayor a cero');
                        this.value = '';
                    }
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <br>
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