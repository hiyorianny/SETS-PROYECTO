<?php
require __DIR__.'/../../Backend/auth/controller/guarda.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Zonas Comunes</title>
    <link rel="stylesheet" href="css/zonas_comunes.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/guarda.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">
                <b style="font-size: 30px;color:aliceblue"> Guarda de Seguridad - <?php echo htmlspecialchars($Usuario); ?> </b></a>
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
                            <div class="offcanvas-header">
                                <img src="img/ayudar (1).png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./ayuda.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Ayuda</b></a>
                                </center>
                            </div>
                            <center>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br><br>

     <main>
        <br><br>
        <section class="zones-section container mt-5">
            <h1 class="title text-center mb-5"><b>Zonas Comunes</b></h1>
            <div class="row" id="zonasContainer">
                <!-- Las zonas se cargarán aquí dinámicamente -->
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando zonas comunes...</p>
                </div>
            </div>
        </section>
        <a href="inicioprincipal.php" class="btn btn-outline-danger btn-lg">Volver</a>
    </main>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="JAVA/main.js"></script>

 <script>
        // Función para cargar las zonas comunes desde la API
        async function cargarZonasComunes() {
            try {
                const response = await fetch('http://192.168.1.102:3001/api/zonas-comunes');
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const zonas = await response.json();
                
                if (!zonas || zonas.length === 0) {
                    throw new Error('No se encontraron zonas comunes');
                }
                
                mostrarZonas(zonas);
            } catch (error) {
                console.error('Error al cargar zonas:', error);
                document.getElementById('zonasContainer').innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar las zonas comunes: ${error.message}
                        <button onclick="cargarZonasComunes()" class="btn btn-sm btn-warning mt-2">
                            Reintentar
                        </button>
                    </div>
                `;
            }
        }

        // Función para mostrar las zonas en el HTML
        function mostrarZonas(zonas) {
            const container = document.getElementById('zonasContainer');
            let html = '';
            
            zonas.forEach(zona => {
                const pagina = obtenerPaginaPorZona(zona.idZona);
                
                html += `
                    <div class="col-12 col-md-6">
                        <article class="zone">
                            <button class="zone-type-btn">
                                <h3>${escapeHtml(zona.idZona)}</h3>
                            </button>
                            <div class="video-wrapper">
                                <video src="${escapeHtml(zona.url_videos)}" autoplay loop muted></video>
                            </div>
                            <h2 class="zone-description">${escapeHtml(zona.descripcion)}</h2>
                                   <a href="${pagina}?id=${escapeHtml(zona.idZona)}" class="btn btn-success btn-zone">
                                    <i class="fas fa-calendar-alt me-2"></i>Ver Horarios
                                </a>
                        </article>
                        <br><br>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function obtenerPaginaPorZona(idZona) {
            switch (idZona.toString()) {
                case '2': return 'solicitarbbq.php';
                case '1': return 'solicitarfutbol.php';
                case '3': return 'solicitarsalon.php';
                case '4': return 'solicitarvoley.php';
                case '5': return 'solicitargym.php';
                default: return '#';
            }
        }


        function escapeHtml(unsafe) {
            return unsafe
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }


        document.addEventListener('DOMContentLoaded', cargarZonasComunes);
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
</body>

</html>