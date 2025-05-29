<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Añadir Anuncio</title>
    <link rel="stylesheet" href="css/añadiranuncio.css?v=<?php echo (rand()); ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header>
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
  
    <br><br><br><br><br><br><br><br>
    <div class="container">
        <section class="login-content">
            <form id="anuncioForm">
                <img src="img/alt.png" alt="Logo" class="imgp">
                <h2 class="title">Añadir Anuncio</h2>

                <div class="input-div one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-easel" viewBox="0 0 16 16">
                        <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0M2 3v7h12V3z" />
                    </svg>
                    <div class="div">
                        <h5>Nombre Del Anuncio</h5>
                        <input type="text" class="input" id="titulo" name="titulo" required>
                    </div>
                </div>

                <div class="input-div one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-easel" viewBox="0 0 16 16">
                        <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0M2 3v7h12V3z" />
                    </svg>
                    <div class="div">
                        <h5>Descripción Del Anuncio</h5>
                        <input type="text" class="input" id="descripcion" name="descripcion" required>
                    </div>
                </div>

                <div class="input-div one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg>
                    <div class="div">
                        <h5 class="input-title">Fecha</h5>
                        <input type="date" class="input" id="fechaPublicacion" name="fechaPublicacion" required>
                    </div>
                </div>

                <div class="input-div one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg>
                    <div class="div">
                        <h5 class="input-title">Hora</h5>
                        <input type="time" class="input" id="horaPublicacion" name="horaPublicacion" required>
                    </div>
                </div>

                <div class="input-div one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-easel" viewBox="0 0 16 16">
                        <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0M2 3v7h12V3z" />
                    </svg>
                    <div class="div">
                        <h5>Persona</h5>
                        <input type="text" class="input" id="persona" name="persona" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Enviar</button>
                <a href="inicioprincipal.php" class="btn btn-danger">VOLVER</a>
            </form>
        </section>
    </div>
    <script type="text/javascript" src="JAVA/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('anuncioForm');
            const fechaInput = document.getElementById('fechaPublicacion');
            const horaInput = document.getElementById('horaPublicacion');

            // Configurar fecha mínima como hoy
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const fechaHoy = yyyy + '-' + mm + '-' + dd;
            fechaInput.setAttribute('min', fechaHoy);

            // Manejar envío del formulario
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validar fecha y hora
                const fechaSeleccionada = new Date(fechaInput.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                if (fechaSeleccionada < hoy) {
                    alert('No puedes seleccionar una fecha pasada');
                    return false;
                }

                if (fechaSeleccionada.getTime() === hoy.getTime()) {
                    const ahora = new Date();
                    const horaActual = ahora.getHours();
                    const minutoActual = ahora.getMinutes();

                    const [horaSeleccionada, minutoSeleccionado] = horaInput.value.split(':').map(Number);

                    if (horaSeleccionada < horaActual ||
                        (horaSeleccionada === horaActual && minutoSeleccionado < minutoActual)) {
                        alert('No puedes seleccionar una hora pasada para el día de hoy');
                        return false;
                    }
                }

                // Obtener valores del formulario
                const titulo = document.getElementById('titulo').value;
                const descripcion = document.getElementById('descripcion').value;
                const persona = document.getElementById('persona').value;
                const fechaPublicacion = fechaInput.value;
                const horaPublicacion = horaInput.value;

                // Crear objeto con los datos
                const anuncioData = {
                    titulo,
                    descripcion,
                    persona,
                    fechaPublicacion,
                    horaPublicacion,
                    apart: null, 
                    img_anuncio: null 
                };

                try {
                    const response = await fetch('http://192.168.1.102:3001/api/anunciossubir', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(anuncioData)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Anuncio creado exitosamente');
                        window.location.href = 'inicioprincipal.php';
                    } else {
                        alert(data.error || 'Error al crear el anuncio');
                        console.error('Error del servidor:', data);
                    }
                } catch (error) {
                    console.error('Error de red:', error);
                    alert('Hubo un error al conectar con el servidor');
                }
            });

            // Validaciones adicionales para fecha y hora
            fechaInput.addEventListener('change', function() {
                const fechaSeleccionada = new Date(this.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                if (fechaSeleccionada < hoy) {
                    alert('No puedes seleccionar una fecha pasada');
                    this.value = fechaHoy;
                }
            });

            horaInput.addEventListener('change', function() {
                const fechaSeleccionada = new Date(fechaInput.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                if (fechaSeleccionada.getTime() === hoy.getTime()) {
                    const ahora = new Date();
                    const horaActual = ahora.getHours();
                    const minutoActual = ahora.getMinutes();

                    const [horaSeleccionada, minutoSeleccionado] = this.value.split(':').map(Number);

                    if (horaSeleccionada < horaActual ||
                        (horaSeleccionada === horaActual && minutoSeleccionado < minutoActual)) {
                        alert('No puedes seleccionar una hora pasada para el día de hoy');
                        const hora = horaActual;
                        const minuto = minutoActual + 1;
                        this.value = `${String(hora).padStart(2, '0')}:${String(minuto).padStart(2, '0')}`;
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <br><br><br><br><br>
</body>

</html>