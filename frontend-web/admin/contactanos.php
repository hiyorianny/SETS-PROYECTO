<?php
require __DIR__ . '/../../Backend/auth/controller/admin.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Responder Dudas</title>
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
        <br><br>
        <br><br>
        <br><br>
        <main>
            <div class="alert alert-success" role="alert">
                <h1>Contactarnos! </h1>
                <center>
                    <h4>Responder Dudas e Inquietudes</h4>
                </center>
            </div>
            <center>
                <div class="barra">
                    <div class="sombra"></div>
                    <input type="text" placeholder="Buscar contacto..." id="searchInput" class="form-control">
                </div>
            </center>

            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="contactTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Id_Contactarnos</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Comentario</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaContactos">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border text-success" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            <p>Cargando datos de contactos...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <center>
            <a href="inicioprincipal.php" class="btn btn-success btn-lg">
                <center>Volver </center>
            </a>
        </center>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            async function cargarContactos() {
                try {
                    const response = await fetch('http://192.168.1.102:3001/api/contactarnos');
                    if (!response.ok) {
                        throw new Error('Error al obtener los datos');
                    }
                    const contactos = await response.json();

                    const tabla = document.getElementById('tablaContactos');
                    tabla.innerHTML = '';

                    if (contactos.length === 0) {
                        tabla.innerHTML = '<tr><td colspan="7" class="text-center">No hay contactos registrados</td></tr>';
                        return;
                    }

                    contactos.forEach(contacto => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                        <td>${contacto.idcontactarnos}</td>
                        <td>${contacto.nombre}</td>
                        <td>${contacto.correo}</td>
                        <td>${contacto.telefono || '-'}</td>
                        <td>${contacto.comentario}</td>
                        <td>${new Date(contacto.fecha).toLocaleString()}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="eliminarContacto(${contacto.idcontactarnos})">
                                Eliminar
                            </button>
                        </td>
                    `;
                        tabla.appendChild(fila);
                    });
                } catch (error) {
                    console.error('Error:', error);
                    document.getElementById('tablaContactos').innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Error al cargar los datos: ${error.message}
                            <button onclick="cargarContactos()" class="btn btn-sm btn-warning">Reintentar</button>
                        </td>
                    </tr>
                `;
                }
            }


            async function eliminarContacto(id) {
                if (!confirm('¿Estás seguro de que deseas eliminar este contacto?')) {
                    return;
                }

                try {
                    const response = await fetch(`http://192.168.1.102:3001/api/contactarnos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer <?php echo $_COOKIE['token'] ?? ''; ?>'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Error al eliminar el contacto');
                    }

                    const result = await response.json();
                    if (result.success) {
                        alert('Contacto eliminado exitosamente');
                        cargarContactos();
                    } else {
                        throw new Error(result.message || 'Error al eliminar el contacto');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert(`Error al eliminar el contacto: ${error.message}`);
                }
            }


            function buscarContactos() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toUpperCase();
                const table = document.getElementById('contactTable');
                const tr = table.getElementsByTagName('tr');

                for (let i = 1; i < tr.length; i++) {
                    let match = false;
                    const td = tr[i].getElementsByTagName('td');

                    for (let j = 0; j < td.length - 1; j++) {
                        if (td[j]) {
                            const txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                match = true;
                                break;
                            }
                        }
                    }

                    tr[i].style.display = match ? '' : 'none';
                }
            }


            document.addEventListener('DOMContentLoaded', function() {
                cargarContactos();
                document.getElementById('searchInput').addEventListener('keyup', buscarContactos);
            });
        </script>
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
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("searchInput");
                const table = document.getElementById("contactTable");
                const rows = table.getElementsByTagName("tr");

                searchInput.addEventListener("input", function() {
                    const searchText = searchInput.value.toLowerCase();

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
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("searchInput");
                const table = document.getElementById("contactTable");
                const rows = table.getElementsByTagName("tr");

                searchInput.addEventListener("input", function() {
                    const searchText = searchInput.value.toLowerCase();

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
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
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