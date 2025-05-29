<?php
require __DIR__ . '/../../Backend/auth/controller/guarda.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Parqueadero Visitante</title>
    <link rel="stylesheet" href="css/parqueadero.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">


</head>

<body>
    <header>
        <div class="topbar">
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
                            </ul>
                            <div class="offcanvas-header">
                                <img src="img/ayudar (1).png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top">
                                <center>
                                    <a href="./ayuda.php" class="btn" id="offcanvasNavbarLabel" style="text-align: center;"><b>Ayuda</b></a>
                                </center>
                            </div>
                            <center>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <br><br>
        <br><br>
        <br><br>

        <div class="container">

            <div class="alert alert-success" role="alert" style="text-align: center; font-size: 24px;">
                <b>Estado de Parqueaderos Visitantes</b>
            </div>
            <div class="row mb-5" id="estadoParqueaderosContainer">
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando estado de parqueaderos...</p>
                </div>
            </div>


            <div class="alert alert-success" role="alert" style="text-align: center; font-size: 24px;"><b>Solicitudes de Parqueadero Visitante</b></div>


            <div class="col-sm-12 col-md-12 col-lg-12 mt-5">
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID Solicitud</th>
                            <th scope="col">Apartamento</th>
                            <th scope="col">Parqueadero</th>
                            <th scope="col">Visitante</th>
                            <th scope="col">Placa</th>
                            <th scope="col">Color</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha Final</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="solicitudesTableBody">
                        <!-- Las solicitudes se cargarán aquí dinámicamente -->
                        <tr>
                            <td colspan="13" class="text-center">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p>Cargando solicitudes...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>

        </div>
        <div class="container mt-5">
            <a href="parqueaderocarro.php" class="btn btn-success">Volver</a>
        </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta solicitud?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const API_BASE_URL = 'http://192.168.1.102:3001/api';
            let solicitudes = [];
            let deleteId = null;
            let confirmDeleteModal = null;

            // Función para manejar errores de fetch
            async function handleFetch(url, options = {}) {
                try {
                    const response = await fetch(url, {
                        ...options,
                        headers: {
                            'Content-Type': 'application/json',
                            ...options.headers
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || `Error HTTP: ${response.status}`);
                    }

                    return await response.json();
                } catch (error) {
                    console.error(`Error en petición a ${url}:`, error);
                    throw error;
                }
            }

            async function initApp() {
                try {
                    // Verificar que el modal existe antes de inicializarlo
                    const modalElement = document.getElementById('confirmDeleteModal');
                    if (!modalElement) {
                        throw new Error('No se encontró el elemento del modal');
                    }

                    // Inicializar componentes
                    confirmDeleteModal = new bootstrap.Modal(modalElement);
                    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

                    // Cargar datos iniciales
                    await Promise.all([
                        cargarEstadoParqueaderos(),
                        cargarSolicitudes()
                    ]);

                } catch (error) {
                    console.error('Error al inicializar:', error);
                    mostrarError('Error al cargar la aplicación', error);
                }
            }

            // Función para mostrar errores
            function mostrarError(titulo, error) {
                Swal.fire({
                    icon: 'error',
                    title: titulo,
                    text: error.message || 'Error desconocido',
                    footer: 'Intente recargar la página'
                });
            }

            // Cargar estado de parqueaderos
            async function cargarEstadoParqueaderos() {
                try {
                    const data = await handleFetch(`${API_BASE_URL}/solicitudes-parqueadero/estado`);
                    mostrarEstadoParqueaderos(data);
                } catch (error) {
                    document.getElementById('estadoParqueaderosContainer').innerHTML = `
                <div class="alert alert-danger">
                    Error: ${error.message}
                    <button onclick="cargarEstadoParqueaderos()" class="btn btn-sm btn-warning">Reintentar</button>
                </div>
            `;
                }
            }


            function mostrarEstadoParqueaderos(parqueaderos) {
                const container = document.getElementById('estadoParqueaderosContainer');

                if (parqueaderos.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">No hay parqueaderos registrados</div>';
                    return;
                }

                container.innerHTML = parqueaderos.map(parqueadero => {

                    const numeroParqueadero = parqueadero.parqueadero || parqueadero.parqueadero_visitante || 'N/A';
                    const estado = parqueadero.estado || 'desconocido';

                    return `
            <div class="col-md-4 mb-4">
                <div class="card parking-card parking-status-${estado.toLowerCase()}">
                    <div class="card-header">
                        <h5 class="card-title">Parqueadero ${parqueadero.visitante}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Estado:</strong> ${estado.charAt(0).toUpperCase() + estado.slice(1)}<br>
                            ${estado !== 'disponible' ? `
                                <strong>Visitante:</strong> ${parqueadero.visitante || 'N/A'}<br>
                                <strong>Placa:</strong> ${parqueadero.placa || 'N/A'}<br>
                                <strong>Horario:</strong> ${parqueadero.horario || 'N/A'}
                            ` : '<strong>Disponible para reserva</strong>'}
                        </p>
                    </div>
                </div>
            </div>
        `;
                }).join('');
            }

            // Cargar solicitudes
            async function cargarSolicitudes() {
                try {
                    const data = await handleFetch(`${API_BASE_URL}/solicitudes-parqueadero`);
                    solicitudes = data;
                    mostrarSolicitudes();
                } catch (error) {
                    document.getElementById('solicitudesTableBody').innerHTML = `
                <tr>
                    <td colspan="13" class="text-center text-danger">
                        Error: ${error.message}
                        <button onclick="cargarSolicitudes()" class="btn btn-sm btn-warning">Reintentar</button>
                    </td>
                </tr>
            `;
                }
            }

            // Mostrar solicitudes
            function mostrarSolicitudes() {
                const tbody = document.getElementById('solicitudesTableBody');

                if (!Array.isArray(solicitudes) || solicitudes.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="13" class="text-center">No hay solicitudes</td></tr>';
                    return;
                }

                tbody.innerHTML = solicitudes.map(solicitud => `
            <tr>
                <td>${solicitud.id_solicitud}</td>
                <td>${solicitud.id_apartamento || 'N/A'}</td>
                <td>${solicitud.parqueadero_visitante}</td>
                <td>${solicitud.nombre_visitante || 'N/A'}</td>
                <td>${solicitud.placaVehiculo || 'N/A'}</td>
                <td>${solicitud.colorVehiculo || 'N/A'}</td>
                <td>${solicitud.tipoVehiculo || 'N/A'}</td>
                <td>${solicitud.modelo || 'N/A'}</td>
                <td>${solicitud.marca || 'N/A'}</td>
                <td>${solicitud.fecha_inicio ? new Date(solicitud.fecha_inicio).toLocaleString() : 'N/A'}</td>
                <td>${solicitud.fecha_final ? new Date(solicitud.fecha_final).toLocaleString() : 'N/A'}</td>
                <td>${solicitud.estado || 'N/A'}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="mostrarConfirmacionEliminar(${solicitud.id_solicitud})">
                        Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
            }

            // Funciones para eliminar
            function mostrarConfirmacionEliminar(id) {
                deleteId = id;
                confirmDeleteModal.show();
            }

            async function confirmDelete() {
                if (!deleteId) return;

                try {
                    await handleFetch(`${API_BASE_URL}/solicitudes-parqueadero/${deleteId}`, {
                        method: 'DELETE'
                    });

                    Swal.fire('Éxito', 'Solicitud eliminada', 'success');
                    await cargarSolicitudes();
                    await cargarEstadoParqueaderos();
                } catch (error) {
                    mostrarError('Error al eliminar', error);
                } finally {
                    confirmDeleteModal.hide();
                    deleteId = null;
                }
            }

            // Iniciar la aplicación
            document.addEventListener('DOMContentLoaded', initApp);
        </script>
    </main>
    <style>
        .parking-card {
            transition: all 0.3s ease;
        }

        .parking-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .parking-status-ocupado {
            background-color: #ff6b6b;
            color: white;
        }

        .parking-status-reservado {
            background-color: rgb(102, 255, 153);
            color: black;
        }

        .parking-status-disponible {
            background-color: rgb(19, 88, 70);
            color: white;
        }
    </style>
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