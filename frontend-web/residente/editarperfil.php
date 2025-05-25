<?php
require '../../Backend/auth/controller/residente.php';
include_once "conexion.php";


$sql = "SELECT r.id_Registro, r.PrimerNombre, r.SegundoNombre, r.PrimerApellido, 
               r.SegundoApellido, r.Correo, r.Usuario, r.numeroDocumento,
               rd.Roldescripcion, r.imagenPerfil, td.descripcionDoc AS tipodoc, 
               r.telefonoUno, r.telefonoDos, r.apartamento, r.tipo_propietario
        FROM registro r
        JOIN rol rd ON r.idRol = rd.id
        JOIN tipodoc td ON r.Id_tipoDocumento = td.idtDoc
        WHERE r.id_Registro = ?";

$stmt = $base_de_datos->prepare($sql);
$stmt->execute([$idRegistro]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    error_log("No se encontró perfil para ID: $idRegistro, Usuario: $Usuario");
    die("Error: No se encontraron datos del perfil. Por favor contacte al administrador.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $base_de_datos->beginTransaction();
        

        $imagenPerfil = $userData['imagenPerfil']; 
        
        if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagenPerfil']['tmp_name'];
            $fileName = uniqid() . '_' . basename($_FILES['imagenPerfil']['name']);
            $fileSize = $_FILES['imagenPerfil']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Validaciones
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $check = getimagesize($fileTmpPath);
            
            if ($check === false) {
                throw new Exception("El archivo no es una imagen válida.");
            }
            
            if ($fileSize > 2000000) { // 2MB
                throw new Exception("El archivo es demasiado grande (máximo 2MB).");
            }
            
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception("Solo se permiten archivos JPG, JPEG, PNG y GIF.");
            }
            
            // Directorio de uploads
            $targetDir = "uploads/perfiles/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $targetFilePath = $targetDir . $fileName;
            
            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                $imagenPerfil = $targetFilePath;
                
                // Eliminar imagen anterior si existe y no es la predeterminada
                if ($userData['imagenPerfil'] && 
                    $userData['imagenPerfil'] != 'img/guarda.png' && 
                    file_exists($userData['imagenPerfil'])) {
                    unlink($userData['imagenPerfil']);
                }
            } else {
                throw new Exception("Hubo un error al subir la imagen.");
            }
        }
        
        // Recoger datos del formulario
        $data = [
            'PrimerNombre' => $_POST['PrimerNombre'] ?? $userData['PrimerNombre'],
            'SegundoNombre' => $_POST['SegundoNombre'] ?? $userData['SegundoNombre'],
            'PrimerApellido' => $_POST['PrimerApellido'] ?? $userData['PrimerApellido'],
            'SegundoApellido' => $_POST['SegundoApellido'] ?? $userData['SegundoApellido'],
            'Correo' => $_POST['Correo'] ?? $userData['Correo'],
            'telefonoUno' => $_POST['telefonoUno'] ?? $userData['telefonoUno'],
            'telefonoDos' => $_POST['telefonoDos'] ?? $userData['telefonoDos'],
         
            'imagenPerfil' => $imagenPerfil,
            'id_Registro' => $idRegistro
        ];
        
        // Actualizar en la base de datos
        $sql = "UPDATE registro SET 
                PrimerNombre = :PrimerNombre,
                SegundoNombre = :SegundoNombre,
                PrimerApellido = :PrimerApellido,
                SegundoApellido = :SegundoApellido,
                Correo = :Correo,
                telefonoUno = :telefonoUno,
                telefonoDos = :telefonoDos,
                imagenPerfil = :imagenPerfil
                WHERE id_Registro = :id_Registro";
        
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute($data);
        
        // Actualizar contraseña si se proporcionó
        if (!empty($_POST['Clave'])) {
            $claveEncriptada = password_hash($_POST['Clave'], PASSWORD_DEFAULT);
            $sql = "UPDATE registro SET Clave = ? WHERE id_Registro = ?";
            $stmt = $base_de_datos->prepare($sql);
            $stmt->execute([$claveEncriptada, $idRegistro]);
        }
        
        $base_de_datos->commit();
        $_SESSION['success_message'] = "Perfil actualizado correctamente";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
        
    } catch (Exception $e) {
        $base_de_datos->rollBack();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
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
    <br><br>
  
    <br>
    <br>
    <br>
  
    <main>
        <section class="profile-card" style="border: 6px solid #052910;">
            <!-- Mostrar mensajes de éxito/error -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="alert alert-success" role="alert">
                    <center>
                        <h2 class="profile-name">Editar Perfil</h2>
                    </center>
                </div>
                
                <div class="text-center">
                    <img id="imagenSeleccionada" src="<?= htmlspecialchars($userData['imagenPerfil'] ?? 'img/guarda.png'); ?>" 
                         alt="Imagen de Perfil" width="120" style="border-radius: 50%;"><br>
                    <input type="file" name="imagenPerfil" onchange="mostrarImagenSeleccionada(this);" 
                           style="color: rgb(45, 110, 59);"><br>
                </div>
                
                <br>
                <label for="PrimerNombre">Primer Nombre:</label><br>
                <input type="text" id="PrimerNombre" name="PrimerNombre" 
                       value="<?= htmlspecialchars($userData['PrimerNombre']); ?>" required><br>

                <label for="SegundoNombre">Segundo Nombre:</label><br>
                <input type="text" id="SegundoNombre" name="SegundoNombre" 
                       value="<?= htmlspecialchars($userData['SegundoNombre']); ?>"><br>

                <label for="PrimerApellido">Primer Apellido:</label><br>
                <input type="text" id="PrimerApellido" name="PrimerApellido" 
                       value="<?= htmlspecialchars($userData['PrimerApellido']); ?>" required><br>

                <label for="SegundoApellido">Segundo Apellido:</label><br>
                <input type="text" id="SegundoApellido" name="SegundoApellido" 
                       value="<?= htmlspecialchars($userData['SegundoApellido']); ?>"><br>

                <label for="Correo">Correo Electrónico:</label><br>
                <input type="email" id="Correo" name="Correo" 
                       value="<?= htmlspecialchars($userData['Correo']); ?>" required><br>

                <label for="telefonoUno">Teléfono Principal:</label><br>
                <input type="text" id="telefonoUno" name="telefonoUno" 
                       value="<?= htmlspecialchars($userData['telefonoUno']); ?>"><br>
                       
                <label for="telefonoDos">Teléfono Secundario:</label><br>
                <input type="text" id="telefonoDos" name="telefonoDos" 
                       value="<?= htmlspecialchars($userData['telefonoDos']); ?>"><br>
                       

                <label for="Usuario">Usuario (no editable):</label><br>
                <input type="text" id="Usuario" name="Usuario" 
                       value="<?= htmlspecialchars($userData['Usuario']); ?>" readonly><br>
                       
                <label for="Clave">Nueva Contraseña (dejar en blanco para no cambiar):</label><br>
                <input type="password" id="Clave" name="Clave"><br>
                
                <input type="submit" value="Guardar Cambios" class="btn btn-success" style="margin-top: 10px;">
            </form>
        </section>
        <a href="perfil.php" type="button" class="btn btn-danger btn-lg">Volver</a>
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