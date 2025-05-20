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


    if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagenPerfil']['tmp_name'];
        $fileName = basename($_FILES['imagenPerfil']['name']);
        $fileSize = $_FILES['imagenPerfil']['size'];
        $fileType = $_FILES['imagenPerfil']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

       
        $check = getimagesize($fileTmpPath);
        if ($check === false) {
            echo "El archivo no es una imagen.";
            exit;
        }

        
        if ($fileSize > 2000000) {
            echo "El archivo es demasiado grande.";
            exit;
        }


        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) {
            echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            exit;
        }

     
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
           
            $sql = "UPDATE registro SET imagenPerfil = ? WHERE Usuario = ?";
            $stmt = $base_de_datos->prepare($sql);
            if ($stmt->execute([$targetFilePath, $Usuario])) {
                echo "La imagen se ha subido correctamente.";
            } else {
                echo "Hubo un error al actualizar la base de datos.";
            }
        } else {
            echo "Hubo un error al subir la imagen.";
        }
    }



$sql = "SELECT r.id_Registro, r.PrimerNombre, r.SegundoNombre, r.PrimerApellido, r.Clave , r.apartamento , r.tipo_propietario,  r.SegundoApellido, r.Correo, r.Usuario, r.numeroDocumento,
                rd.Roldescripcion, r.imagenPerfil, td.descripcionDoc AS tipodoc, r.telefonoUno, r.telefonoDos
        FROM registro r
        JOIN rol rd ON r.idRol = rd.id
        JOIN tipodoc td ON r.Id_tipoDocumento = td.idtDoc
        WHERE r.Usuario = ?";

$stmt = $base_de_datos->prepare($sql);
$stmt->execute([$Usuario]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("Error: No se encontraron datos del perfil.");
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS -Editar Perfil</title>
    <link rel="stylesheet" href="css/perfil.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente </b>
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
        <section class="chat-container" id="chatContainer">
            <header class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </header>
            <div class="chat-messages" id="chatMessages">
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </section>
    </main>
    <br>
    <br>
    <br>
    <div class="perfil">
        <center>
            <div class="alert alert-success" role="alert">
                <h1>Perfil de Usuario</h1>
            </div>
            <div class="info-perfil">
                <?php if ($userData['imagenPerfil']): ?>
                    <img src="<?php echo htmlspecialchars($userData['imagenPerfil']); ?>" alt="Imagen de Perfil" class="imagen-perfil">
                <?php endif; ?>
                <p><b>Rol:</b> <?php echo htmlspecialchars($userData['Roldescripcion']); ?></p>
                <p><b>Primer Nombre:</b> <?php echo htmlspecialchars($userData['PrimerNombre']); ?></p>
                <p><b>Segundo Nombre:</b> <?php echo htmlspecialchars($userData['SegundoNombre']); ?></p>
                <p><b>Primer Apellidos:</b> <?php echo htmlspecialchars($userData['PrimerApellido']); ?></p>
                <p><b>Segundo Apellidos:</b> <?php echo  htmlspecialchars($userData['SegundoApellido']); ?></p>
                <p><b>Tipo Propietario:</b><?php echo htmlspecialchars($userData['tipo_propietario']); ?></p>
                <p><b>Apartamento: </b><?php echo htmlspecialchars($userData['apartamento']); ?></p>
                <p><b>Tipo de Documento:</b> <?php echo htmlspecialchars($userData['tipodoc']); ?></p>

                <p><b>Numero de Documento </b><?php echo htmlspecialchars($userData['numeroDocumento']); ?></p>
                <p><b>Teléfono 1:</b> <?php echo htmlspecialchars($userData['telefonoUno']); ?></p>
                <p><b>Teléfono 2: </b><?php echo htmlspecialchars($userData['telefonoDos']); ?></p>
                <p><b>Correo: </b><?php echo htmlspecialchars($userData['Correo']); ?></p>
                <p><b>Usuario:</b> <?php echo htmlspecialchars($userData['Usuario']); ?></p>
               
                <p><b>Eres la persona o tu numero de <br> registro fue el:</b> <?php echo htmlspecialchars($userData['id_Registro']); ?></p>
            </div>
            <br>
            <br>
            <a href="editarperfil.php" class="btn btn-success">Actualizar Datos</a>
            <a href="t.php" class="btn btn-success">Agregar mi Vivienda</a>
            <a href="pagos.php" class="btn btn-success">Pagos</a>
<br>

            <a href="inicioprincipal.php" class="btn btn-danger">Volver</a>
            <a href="../../MODEL/backend/logout.php" class="btn btn-danger" >Cerrar sesión</a>

    </div>
    </center>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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

</html>