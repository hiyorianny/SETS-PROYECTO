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
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Datos de Usuario</title>
    <link rel="stylesheet" href="css/datos_usuario.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <header>
            <div class="topbar">
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
        <br><br>
        <br><br>
        <div class="alert alert-success" role="alert">
            <h1>Datos De Usuarios !</h1>
        </div>
        <center>
        <div class="barra">
            <div class="sombra"></div>
            <input type="text" placeholder="Buscar usuario..." id="searchInput">
            <ion-icon name="search-outline"></ion-icon>
        </div>
    </center>
    <main>
        <section>
            <br>
            <table class="user-table table table-striped" id="userTable">
                <thead>
                    <tr>
                        <th class="cc">N°</th>
                        <th class="cc">Rol</th>
                        <th class="cc">Tipo de Documento</th>
                        <th class="cc">Número de Documento</th>
                        <th class="cc">Nombre</th>
                        <th class="cc">Apellido</th>
                        <th class="cc">Apartamento</th>
                        <th class="cc">Correo</th>
                        <th class="cc">telefonoUno</th>
                        <th class="cc">tipo_propietario</th>
                        <th class="cc">Usuario</th>
                        <th class="cc">Contraseña</th>
                        <th class="cc">Imagen Perfil</th>
                        <th class="cc">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
             
                    try {
                        include_once "conexion.php";

               
                        $stmt = $base_de_datos->query("
                            SELECT r.*, rol.Roldescripcion 
                            FROM registro r 
                            LEFT JOIN rol ON r.idROL = rol.id
                        ");

                        if ($stmt === false) {
                            echo "<tr><td colspan='14'>Error en la consulta SQL.</td></tr>";
                        } else {
                            $i = 1; 
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if ($row) { 
                                    $primerNombre = $row['PrimerNombre'] ?? 'Sin Nombre'; 
                                    $segundoNombre = $row['SegundoNombre'] ?? '';
                                    $primerApellido = $row['PrimerApellido'] ?? '';
                                    $segundoApellido = $row['SegundoApellido'] ?? '';
                                    $apartamento = $row['apartamento'] ?? '';
                                    $correo = $row['Correo'] ?? '';
                                    $telefonoUno = $row['telefonoUno'] ?? '';
                                    $tipo_propietario = $row['tipo_propietario'] ?? '';
                                    $usuario = $row['Usuario'] ?? '';
                                    $clave = $row['Clave'] ?? '';
                                    $imagenPerfil = $row['imagenPerfil'] ?? '';
                                    $rolDescripcion = $row['Roldescripcion'] ?? 'Sin Rol'; 

                                    echo "<tr>
                                        <td>$i</td>
                                        <td>$rolDescripcion</td> <!-- Mostrar rol real -->
                                        <td>Cédula</td> <!-- Ajusta según el tipo de documento -->
                                        <td>{$row['numeroDocumento']}</td>
                                        <td>$primerNombre</td>
                                        <td>$primerApellido</td>
                                        <td>$apartamento</td>
                                        <td>$correo</td>
                                        <td>$telefonoUno</td> 
                                        <td>$tipo_propietario</td>
                                        <td>$usuario</td>
                                        <td>$clave</td>
                                        <td>";
                                    if ($imagenPerfil) {
                                        echo "<img src='$imagenPerfil' alt='Imagen Perfil' width='50' height='50'>";
                                    } else {
                                        echo "Sin Imagen";
                                    }
                                    echo "</td>
                                        <td>
                                            <a href='../../CONTROLLER/eliminar_usuario.php?id_Registro=" . htmlspecialchars($row['id_Registro']) . "' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este usuario?')\">ELIMINAR</a>
                                        </td>
                                    </tr>";

                                    $i++;
                                } else {
                                    
                                    echo "<tr><td colspan='14'>No hay usuarios disponibles.</td></tr>";
                                }
                            }
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='14'>Error: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
        <center>
            <a href="inicioprincipal.php" class="btn btn-success btn-lg">
                <center>Volver</center>
            </a>
        </center>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
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
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const table = document.getElementById("userTable");
            const rows = table.getElementsByTagName("tr");

            searchInput.addEventListener("input", function () {
                const searchText = searchInput.value.toLowerCase();

                // Recorrer todas las filas de la tabla (excepto la primera, que es el encabezado)
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName("td");
                    let match = false;

                  
                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent.toLowerCase();
                        if (cellText.includes(searchText)) {
                            match = true;
                            break; 
                        }
                    }

                
                    if (match) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        });
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

</body>

<br>
    <br>
    <br>
    <footer> 
  <div class="footer-content">
    <li >&copy; 2025 SETS. Todos los derechos reservados.</li>
    <ul>
      <li><a href="#">Términos y Condiciones</a></li>
      <li><a href="#">Política de Privacidad</a></li>
      <li><a href="#">Contacto</a></li>
    </ul>
  </div>
</footer>

</html>