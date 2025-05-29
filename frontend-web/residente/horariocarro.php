<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>
    <header>
        <div class="topbar">
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

                        </div>
                    </div>
                </div>
            </nav>
    </header>
  <main>
        <div class="container mt-5 pt-4">
     <div class="card shadow mb-5">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0"><i class="bi bi-p-square"></i> Estado de Parqueaderos Carro Visitantes</h3>
                </div>
                <div class="card-body">
                    <div class="row" id="estadoParqueaderosContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando disponibilidad...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h3 class="mb-0"><i class="bi bi-car-front"></i> Nueva Solicitud</h3>
                        </div>
                        <div class="card-body">
                            <form id="solicitudForm" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="id_apartamento" class="form-label">Apartamento</label>
                                        <input type="text" class="form-control" id="id_apartamento"  required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="parqueadero_visitante" class="form-label">Parqueadero</label>
                                        <select class="form-select" id="parqueadero_visitante" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="V1">V1</option>
                                            <option value="V2">V2</option>
                                            <option value="V3">V3</option>
                                            <option value="V4">V4</option>
                                            <option value="V5">V5</option>
                                            <option value="V6">V6</option>
                                            <option value="V7">V7</option>
                                            <option value="V8">V8</option>
                                            <option value="V9">V9</option>
                                            <option value="V10">V10</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor seleccione un parqueadero
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="nombre_visitante" class="form-label">Nombre del Visitante</label>
                                        <input type="text" class="form-control" id="nombre_visitante" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese el nombre del visitante
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tipoVehiculo" class="form-label">Tipo de Vehículo</label>
                                        <select class="form-select" id="tipoVehiculo" required>
                                            <option value="carro" selected>Carro</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="placaVehiculo" class="form-label">Placa</label>
                                        <input type="text" class="form-control" id="placaVehiculo" required>
                                        <div class="invalid-feedback">
                                            Por favor ingrese la placa del vehículo
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="marca" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="marca" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="modelo" class="form-label">Modelo</label>
                                        <input type="text" class="form-control" id="modelo" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="colorVehiculo" class="form-label">Color</label>
                                        <input type="text" class="form-control" id="colorVehiculo" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                                        <input type="datetime-local" class="form-control" id="fecha_inicio" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="fecha_final" class="form-label">Fecha y Hora Final</label>
                                        <input type="datetime-local" class="form-control" id="fecha_final" required>
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-success w-100 py-2">
                                            <i class="bi bi-send-check"></i> Enviar Solicitud
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Historial de solicitudes -->
                <div class="col-lg-7">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h3 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Solicitudes</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Parqueadero</th>
                                            <th>Visitante</th>
                                            <th>Vehículo</th>
                                            <th>Fecha/Hora</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="solicitudesTableBody">
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="spinner-border text-success" role="status">
                                                    <span class="visually-hidden">Cargando...</span>
                                                </div>
                                                <p class="mt-2">Cargando historial...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar acción</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        ¿Está seguro de que desea eliminar esta solicitud?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmActionBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

                <br>
                <div class="container mt-5">
                    <a href="parqueaderocarro.php" class="btn btn-success">Volver</a>
                </div>
            </div>
        </div>
        <br>
        </div>
        <br>
        </div>
        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const cards = document.querySelectorAll('.product-card');

                cards.forEach(card => {
                    const number = card.getAttribute('data-number').toLowerCase();
                    if (number.includes(query)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
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

            function showTab(tabId) {
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
                document.querySelector(`.tab-btn[onclick="showTab('${tabId}')"]`).classList.add('active');
            }
        </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
               const API_BASE_URL = 'http://192.168.1.102:3001/api';
        let solicitudes = [];
        let currentActionId = null;
        let confirmModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            document.getElementById('confirmActionBtn').addEventListener('click', confirmAction);
            
            const form = document.getElementById('solicitudForm');
            form.addEventListener('submit', handleSubmit);
            
            loadInitialData();
            
            setupDateValidation();
        });

        async function loadInitialData() {
            try {
                await Promise.all([
                    loadParkingStatus(),
                    loadRequests()
                ]);
            } catch (error) {
                showError('Error al cargar datos', error);
            }
        }

        async function loadParkingStatus() {
            try {
                const response = await fetch(`${API_BASE_URL}/solicitudes-parqueadero/estado`);
                if (!response.ok) throw new Error('Error al cargar estado');
                
                const data = await response.json();
                displayParkingStatus(data);
            } catch (error) {
                document.getElementById('estadoParqueaderosContainer').innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar disponibilidad: ${error.message}
                        <button onclick="loadParkingStatus()" class="btn btn-sm btn-warning">Reintentar</button>
                    </div>
                `;
            }
        }

        function displayParkingStatus(parqueaderos) {
            const container = document.getElementById('estadoParqueaderosContainer');
            
            if (!parqueaderos || parqueaderos.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">No hay información de parqueaderos</div>';
                return;
            }
            
            // Filtrar solo parqueaderos con estado diferente a NULL
            const parqueaderosFiltrados = parqueaderos.filter(p => p.estado !== null);
            
            container.innerHTML = parqueaderosFiltrados.map(p => {
                // Manejo seguro de valores NULL
                const estado = p.estado || 'disponible';
                const parqueadero = p.parqueadero || 'N/A';
                const visitante = p.visitante || 'N/A';
                const placa = p.placa || 'N/A';
                const horario = p.horario || 'N/A';
                
                const statusClass = getStatusClass(estado);
                const statusText = getStatusText(estado);
                
                const details = estado !== 'disponible' ? `
                    <div class="mt-2">
                        <small class="d-block"><strong>Visitante:</strong> ${visitante}</small>
                        <small class="d-block"><strong>Placa:</strong> ${placa}</small>
                        <small class="d-block"><strong>Horario:</strong> ${horario}</small>
                    </div>
                ` : '<div class="mt-2"><small>Disponible para reserva</small></div>';
                
                return `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-${statusClass} animate__animated animate__fadeIn">
                            <div class="card-body text-center">
                                <h5 class="card-title">${parqueadero}</h5>
                                <span class="badge bg-${statusClass} mb-2">${statusText}</span>
                                ${details}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getStatusClass(status) {
            switch((status || '').toLowerCase()) {
                case 'ocupado': return 'danger';
                case 'reservado': return 'warning';
                case 'disponible': return 'success';
                default: return 'secondary';
            }
        }

        function getStatusText(status) {
            switch((status || '').toLowerCase()) {
                case 'ocupado': return 'Ocupado';
                case 'reservado': return 'Reservado';
                case 'disponible': return 'Disponible';
                default: return status || 'Desconocido';
            }
        }

        async function loadRequests() {
            try {
                // Filtrar solo solicitudes de carros
                const response = await fetch(`${API_BASE_URL}/solicitudes-parqueadero?tipoVehiculo=carro`);
                if (!response.ok) throw new Error('Error al cargar solicitudes');
                
                solicitudes = await response.json();
                displayRequests();
            } catch (error) {
                document.getElementById('solicitudesTableBody').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger py-3">
                            Error al cargar historial: ${error.message}
                            <button onclick="loadRequests()" class="btn btn-sm btn-warning">Reintentar</button>
                        </td>
                    </tr>
                `;
            }
        }

        function displayRequests() {
            const tbody = document.getElementById('solicitudesTableBody');
            
            if (!solicitudes || solicitudes.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-info-circle"></i> No hay solicitudes registradas
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Filtrar solo solicitudes de carros (por si acaso)
            const solicitudesCarros = solicitudes.filter(s => s.tipoVehiculo === 'carro');
            
            tbody.innerHTML = solicitudesCarros.map(solicitud => {
                const statusClass = solicitud.estado === 'aprobado' ? 'success' : 
                                  solicitud.estado === 'rechazado' ? 'danger' : 'warning';
                
                // Manejo seguro de valores NULL
                const parqueadero = solicitud.parqueadero_visitante || 'N/A';
                const visitante = solicitud.nombre_visitante || 'N/A';
                const tipoVehiculo = solicitud.tipoVehiculo || 'N/A';
                const placa = solicitud.placaVehiculo || 'Sin placa';
                const estado = solicitud.estado || 'pendiente';
                
                return `
                    <tr class="animate__animated animate__fadeIn">
                        <td>${parqueadero}</td>
                        <td>${visitante}</td>
                        <td>
                            <small class="d-block">${tipoVehiculo}</small>
                            <small class="text-muted">${placa}</small>
                        </td>
                        <td>
                            <small class="d-block">${formatDate(solicitud.fecha_inicio)}</small>
                            <small class="text-muted">a ${formatDate(solicitud.fecha_final)}</small>
                        </td>
                        <td>
                            <span class="badge bg-${statusClass}">
                                ${estado}
                            </span>
                        </td>
                        <td>
                            ${estado === 'pendiente' ? `
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="showConfirmModal(${solicitud.id_solicitud}, 'eliminar')"
                                    title="Cancelar solicitud">
                                <i class="bi bi-trash"></i>
                            </button>
                            ` : ''}
                        </td>
                    </tr>
                `;
            }).join('');
        }


        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleString('es-CO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function setupDateValidation() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFinal = document.getElementById('fecha_final');
    
    // Establecer fecha mínima como la fecha/hora actual
    const now = new Date();
    const nowISO = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    fechaInicio.min = nowISO;
    
    fechaInicio.addEventListener('change', function() {
        // Validar que la fecha de inicio no sea en el pasado
        const fechaInicioValue = new Date(fechaInicio.value);
        if (fechaInicioValue < now) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha inválida',
                text: 'La fecha de inicio no puede ser en el pasado',
                timer: 3000
            });
            fechaInicio.value = '';
            return;
        }
        
        // Establecer fecha mínima para fecha final (1 hora después de la fecha de inicio)
        if (fechaInicio.value) {
            const minFechaFinal = new Date(fechaInicio.value);
            minFechaFinal.setHours(minFechaFinal.getHours() + 1);
            fechaFinal.min = minFechaFinal.toISOString().slice(0, 16);
            
            if (fechaFinal.value && new Date(fechaFinal.value) <= minFechaFinal) {
                fechaFinal.value = '';
                fechaFinal.setCustomValidity('La fecha final debe ser al menos 1 hora después de la fecha de inicio');
            }
        }
    });
    
    fechaFinal.addEventListener('change', function() {
        if (fechaInicio.value && new Date(fechaFinal.value) <= new Date(fechaInicio.value)) {
            Swal.fire({
                icon: 'error',
                title: 'Rango inválido',
                text: 'La fecha final debe ser posterior a la fecha de inicio',
                timer: 3000
            });
            fechaFinal.value = '';
            fechaFinal.setCustomValidity('La fecha final debe ser posterior a la de inicio');
        } else {
            fechaFinal.setCustomValidity('');
            
            // Validar que la reserva no exceda 24 horas
            if (fechaInicio.value && fechaFinal.value) {
                const inicio = new Date(fechaInicio.value);
                const fin = new Date(fechaFinal.value);
                const diffHours = (fin - inicio) / (1000 * 60 * 60);
                
                if (diffHours > 24) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tiempo excedido',
                        text: 'La reserva no puede exceder las 24 horas',
                        timer: 3000
                    });
                    fechaFinal.value = '';
                }
            }
        }
    });
}


        async function handleSubmit(event) {
            event.preventDefault();
            event.stopPropagation();
            
            const form = event.target;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            
            try {
                const formData = {
                    id_apartamento: document.getElementById('id_apartamento').value,
                    parqueadero_visitante: document.getElementById('parqueadero_visitante').value,
                    nombre_visitante: document.getElementById('nombre_visitante').value,
                    placaVehiculo: document.getElementById('placaVehiculo').value,
                    colorVehiculo: document.getElementById('colorVehiculo').value,
                    tipoVehiculo: document.getElementById('tipoVehiculo').value,
                    modelo: document.getElementById('modelo').value,
                    marca: document.getElementById('marca').value,
                    fecha_inicio: document.getElementById('fecha_inicio').value,
                    fecha_final: document.getElementById('fecha_final').value,
                    estado: 'pendiente'
                };
                
                const response = await fetch(`${API_BASE_URL}/solicitudes-parqueadero`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al enviar solicitud');
                }
                
                await Swal.fire({
                    icon: 'success',
                    title: 'Solicitud enviada',
                    text: 'Tu solicitud ha sido registrada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                form.reset();
                form.classList.remove('was-validated');
                
                // Recargar datos
                await Promise.all([
                    loadParkingStatus(),
                    loadRequests()
                ]);
                
            } catch (error) {
                showError('Error al enviar solicitud', error);
            }
        }

        function showConfirmModal(id, action) {
            currentActionId = id;
            const modal = document.getElementById('confirmModal');
            const modalBody = document.getElementById('modalBody');
            const confirmBtn = document.getElementById('confirmActionBtn');
            
            if (action === 'eliminar') {
                modalBody.textContent = '¿Está seguro de que desea cancelar esta solicitud?';
                confirmBtn.textContent = 'Cancelar solicitud';
                confirmBtn.className = 'btn btn-danger';
            }
            
            confirmModal.show();
        }

        async function confirmAction() {
            if (!currentActionId) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/solicitudes-parqueadero/${currentActionId}`, {
                    method: 'DELETE'
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al eliminar solicitud');
                }
                
                await Swal.fire({
                    icon: 'success',
                    title: 'Solicitud cancelada',
                    text: 'La solicitud ha sido cancelada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                confirmModal.hide();
                await Promise.all([
                    loadParkingStatus(),
                    loadRequests()
                ]);
                
            } catch (error) {
                showError('Error al procesar la acción', error);
            } finally {
                currentActionId = null;
            }
        }

        function showError(title, error) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: error.message || 'Ocurrió un error inesperado',
                footer: 'Por favor intente nuevamente'
            });
        }
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