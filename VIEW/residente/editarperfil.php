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


// Preparar la consulta para obtener los datos del perfil
$sql = "SELECT r.id_Registro, r.PrimerNombre, r.SegundoNombre, r.PrimerApellido, r.Clave , r.SegundoApellido, r.Correo, r.Usuario, r.numeroDocumento,
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

// Manejar la subida de la imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la subida de la imagen
    if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagenPerfil']['tmp_name'];
        $fileName = basename($_FILES['imagenPerfil']['name']);
        $fileSize = $_FILES['imagenPerfil']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen real
        $check = getimagesize($fileTmpPath);
        if ($check === false) {
            echo "El archivo no es una imagen.";
            exit;
        }

        // Verificar el tamaño del archivo (máximo 2MB)
        if ($fileSize > 1000000) {
            echo "El archivo es demasiado grande.";
            exit;
        }

        // Permitir ciertos formatos de archivo
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) {
            echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            exit;
        }

        // Definir la ruta de destino y mover el archivo
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            // Actualizar la base de datos con la ruta de la imagen
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

    // Recoger otros datos del formulario
    $PrimerNombre = $_POST['profile-firstname'] ?? '';
    $SegundoNombre = $_POST['profile-secondname'] ?? '';
    $PrimerApellido = $_POST['profile-firstlastname'] ?? '';
    $SegundoApellido = $_POST['profile-secondlastname'] ?? '';
    $Correo = $_POST['profile-email'] ?? '';
    $Usuario = $_POST['profile-username'] ?? '';
    $telefonoUno = $_POST['profile-phone1'] ?? '';
    $telefonoDos = $_POST['profile-phone2'] ?? '';

    // Actualizar el perfil en la base de datos
    $sql = "UPDATE registro   SET 
        PrimerNombre = ?, 
        SegundoNombre = ?, 
        PrimerApellido = ?, 
        SegundoApellido = ?, 
        Correo = ?, 
        telefonoUno = ?,
        telefonoDos = ?,
        Usuario = ? 

    WHERE Usuario = ?";

    $stmt = $base_de_datos->prepare($sql);
    if ($stmt->execute([$PrimerNombre, $SegundoNombre, $PrimerApellido, $SegundoApellido, $Correo, $Usuario, $telefonoUno, $telefonoDos, $Usuario])) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos.";
    }

    // Actualizar la contraseña si se proporciona
    if (!empty($_POST['profile-password'])) {
        $clave = $_POST['profile-password'];
        // Encriptar la contraseña
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        $sql = "UPDATE registro SET Clave = ? WHERE Usuario = ?";
        $stmt = $base_de_datos->prepare($sql);
        if ($stmt->execute([$claveEncriptada, $Usuario])) {
            echo "Clave actualizada con éxito.";
        } else {
            echo "Error al actualizar la clave.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS -Editar Perfil</title>
    <link rel="stylesheet" href="css/editarperfil.css?v=<?php echo (rand()); ?>">
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
        <section id="chatContainer" class="chat-container position-fixed p-5 rounded-3" style="z-index: 1000; bottom: 20px; right: 20px;">
            <div class="chat-header">
                <span id="chatHeader">Chat</span>
                <button class="close-btn" onclick="closeChat()">×</button>
            </div>
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </section>
    </main>
    <br>
    <br>
    <br>
    <main>
        <section class="profile-card" style="border: 6px solid #052910;">
            <form action="procesar_datos.php" method="POST" enctype="multipart/form-data">

                <div class="alert alert-success" role="alert">
                    <center>
                        <h2 class="profile-name"><b>Editar Perfil</b></h2>
                    </center>
                </div>
                <div class="text-center">
                    <img id="imagenSeleccionada" src="<?php echo htmlspecialchars($userData['imagenPerfil'] ?? 'img/resi.png'); ?>" alt="Imagen de Perfil" width="120"><br><br>
                    <input type="file" name="imagenPerfil" onchange="mostrarImagenSeleccionada(this);" style="color: rgb(45, 110, 59);"><br>
                </div>
                <br>
                <label for="profile-firstname"><b>Primer Nombre:</b></label><br>
                <input type="text" id="profile-firstname" name="profile-firstname" value="<?php echo htmlspecialchars($userData['PrimerNombre']); ?>"><br>

                <label for="profile-secondname"><b>Segundo Nombre:</b></label><br>
                <input type="text" id="profile-secondname" name="profile-secondname" value="<?php echo htmlspecialchars($userData['SegundoNombre']); ?>"><br>

                <label for="profile-firstlastname"><b>Primer Apellido:</b></label><br>
                <input type="text" id="profile-firstlastname" name="profile-firstlastname" value="<?php echo htmlspecialchars($userData['PrimerApellido']); ?>"><br>

                <label for="profile-secondlastname"><b>Segundo Apellido:</b></label><br>
                <input type="text" id="profile-secondlastname" name="profile-secondlastname" value="<?php echo htmlspecialchars($userData['SegundoApellido']); ?>"><br>

                <label for="profile-email"><b>Correo Electrónico:</b></label><br>
                <input type="email" id="profile-email" name="profile-email" value="<?php echo htmlspecialchars($userData['Correo']); ?>"><br>

                <label for="profile-phone1"><b>Teléfono Uno :</b></label><br>
                <input type="text" id="profile-phone1" name="profile-phone1" value="<?php echo htmlspecialchars($userData['telefonoUno']); ?>"><br>
                <label for="profile-phone2"><b>Teléfono Dos:</b></label><br>
                <input type="text" id="profile-phone2" name="profile-phone2" value="<?php echo htmlspecialchars($userData['telefonoDos']); ?>"><br>

                <label for="profile-username"><b>Usuario:</b></label><br>
                <input type="text" id="profile-username" name="profile-username" value="<?php echo htmlspecialchars($userData['Usuario']); ?>"><br>

                <label for="profile-password"><b>Nueva Contraseña:</b></label><br>
                <input type="password" id="profile-password" name="profile-password" value="<?php echo htmlspecialchars($userData['Clave']); ?>"><br>

                <input type="submit" value="Guardar Cambios" class="btn btn-success" style="margin-top: 10px;">
            </form>
        </section>
        <a href="perfil.php" type="button" class="btn btn-danger btn-lg"><b>Volver<b></a>
    </main>

    <script>
        function mostrarImagenSeleccionada(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagenSeleccionada').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    </section>
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