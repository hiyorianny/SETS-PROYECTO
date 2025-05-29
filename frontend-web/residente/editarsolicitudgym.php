<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Actualizar GYM</title>
    <link rel="stylesheet" href="css/gym.css?v=<?php echo (rand()); ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
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
    <br><br><br>
    <div class="alert alert-success" role="alert">
        <h2 style="text-align: center;">Actualizar Agendación Del Gimnasio </h2>
        <p>
    </div>
    <br>
    <div class="container">
        <section class="login-content">
            <div class="container">
                <form id="formEditarSolicitud">
                    <img src="img/gym-equipment.png" alt="Logo" class="imgp">

                    <div class="mb-3">
                        <label for="fechainicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" class="form-control" id="fechainicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="Hora_inicio" class="form-label">Hora de Inicio:</label>
                        <input type="time" class="form-control" id="Hora_inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="fechafinal" class="form-label">Fecha de Finalización:</label>
                        <input type="date" class="form-control" id="fechafinal" required>
                    </div>
                    <div class="mb-3">
                        <label for="Hora_final" class="form-label">Hora de Finalización:</label>
                        <input type="time" class="form-control" id="Hora_final" required>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </form>
                <br>
        </section>
    </div>
    <br>
    <br>
    <br>
    <br>
    <a href="solicitargym.php" class="btn btn-danger btn-lg">volver</a>
    <script type="text/javascript" src="JAVA/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const solicitudStr = sessionStorage.getItem('solicitudEditar');
            if (!solicitudStr) {
                alert('No se encontraron datos de la solicitud');
                window.location.href = './solicitargym.php';
                return;
            }

            const solicitud = JSON.parse(solicitudStr);


            const fechaInicio = new Date(solicitud.fechainicio);
            const fechaFinal = new Date(solicitud.fechafinal);

            document.getElementById('fechainicio').value = fechaInicio.toISOString().split('T')[0];
            document.getElementById('Hora_inicio').value = solicitud.Hora_inicio;
            document.getElementById('fechafinal').value = fechaFinal.toISOString().split('T')[0];
            document.getElementById('Hora_final').value = solicitud.Hora_final;


            document.getElementById('formEditarSolicitud').addEventListener('submit', async function(e) {
                e.preventDefault();

                try {

                    const nuevaFechaInicio = document.getElementById('fechainicio').value;
                    const nuevaHoraInicio = document.getElementById('Hora_inicio').value;
                    const nuevaFechaFinal = document.getElementById('fechafinal').value;
                    const nuevaHoraFinal = document.getElementById('Hora_final').value;


                    const fechaOriginal = new Date(solicitud.fechainicio);
                    const fechaOriginalFormateada = fechaOriginal.toISOString().split('T')[0];

                    const datos = {
                        ID_Apartamentooss: solicitud.ID_Apartamentooss,
                        ID_zonaComun: solicitud.ID_zonaComun,
                        fechainicio: nuevaFechaInicio,
                        Hora_inicio: nuevaHoraInicio,
                        fechafinal: nuevaFechaFinal,
                        Hora_final: nuevaHoraFinal,
                        fecha_original: fechaOriginalFormateada,
                        hora_original: solicitud.Hora_inicio
                    };

                    const response = await fetch('http://192.168.1.102:3001/api/solicitudes-zonas/actualizar', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(datos)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Error al actualizar');
                    }

                    if (result.success) {
                        alert('Solicitud actualizada con éxito');
                        window.location.href = './solicitargym.php';
                    } else {
                        throw new Error(result.error || 'Error desconocido al actualizar');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al actualizar: ' + error.message);
                }
            });


            const fechaInicioInput = document.getElementById('fechainicio');
            const fechaFinalInput = document.getElementById('fechafinal');
            const horaInicioInput = document.getElementById('Hora_inicio');
            const horaFinalInput = document.getElementById('Hora_final');

            fechaInicioInput.addEventListener('change', function() {
                fechaFinalInput.min = this.value;
                if (fechaFinalInput.value < this.value) {
                    fechaFinalInput.value = this.value;
                }
            });

            function validarHoras() {
                if (fechaInicioInput.value === fechaFinalInput.value) {
                    if (horaInicioInput.value && horaFinalInput.value) {
                        if (horaInicioInput.value >= horaFinalInput.value) {
                            alert('La hora de inicio debe ser anterior a la hora final');
                            horaFinalInput.value = '';
                        }
                    }
                }
            }

            horaInicioInput.addEventListener('change', validarHoras);
            horaFinalInput.addEventListener('change', validarHoras);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>