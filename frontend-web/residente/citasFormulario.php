<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Reservar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <!-- Biblioteca para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
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
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <br><br>
    <main>
        <br> <br> <br>
        <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;"><b>Agendar Cita</b></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-4 mt-5">
                    <form id="citaForm">
                        <fieldset>
                            <center>
                                <legend><b>Formulario</b></legend>
                            </center>
                            <div class="mb-3">
                                <label for="tipocita" class="form-label">Tipo de cita:</label>
                                <select name="tipocita" id="tipocita" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un tipo</option>
                                    <option value="Administrativo">Administrativo (1h)</option>
                                    <option value="Reclamo">Reclamo (1h)</option>
                                    <option value="Duda">Duda (1h)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fechacita" class="form-label">Fecha:</label>
                                <input type="date" class="form-control" id="fechacita" name="fechacita" required>
                            </div>
                            <div class="mb-3">
                                <label for="horacita" class="form-label">Hora:</label>
                                <input type="time" class="form-control" id="horacita" name="horacita" min="08:00" max="17:00" step="3600" required>
                            </div>
                            <div class="mb-3">
                                <label for="apa" class="form-label">Ingresa tu número de apartamento:</label>
                                <input type="text" class="form-control" id="apa" name="apa" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-8 mt-5">
                    <center>
                        <h2><b>Panel de Citas</b></h2>
                    </center>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Tipo de cita</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Apartamento</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Comentario</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="citasTableBody">
   
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <a href="citas.php" class="btn btn-success">Volver</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script>
        
            const API_BASE_URL = 'http://192.168.1.102:3001/api/citas';

    
            document.addEventListener('DOMContentLoaded', function() {
                loadCitas();
                setupFormValidation();
            });

            
            async function loadCitas() {
                try {
                    const response = await fetch(API_BASE_URL);
                    if (!response.ok) {
                        throw new Error('Error al cargar las citas');
                    }
                    const citas = await response.json();
                    renderCitas(citas);
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Error al cargar las citas', 'danger');
                }
            }

 
            function renderCitas(citas) {
                const tableBody = document.getElementById('citasTableBody');
                tableBody.innerHTML = '';

                citas.forEach(cita => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${escapeHtml(cita.tipocita)}</td>
                        <td>${formatDate(cita.fechacita)}</td>
                        <td>${escapeHtml(cita.horacita)}</td>
                        <td>${escapeHtml(cita.apa)}</td>
                        <td><span class="badge ${getStatusBadgeClass(cita.estado)}">${escapeHtml(cita.estado)}</span></td>
                        <td>${escapeHtml(cita.respuesta || 'Sin respuesta')}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deleteCita(${cita.idcita})">Eliminar</button>
                            <button class="btn btn-success btn-sm mt-1" onclick="generatePDF(${cita.idcita}, '${escapeHtml(cita.tipocita)}', '${escapeHtml(cita.fechacita)}', '${escapeHtml(cita.horacita)}', '${escapeHtml(cita.apa)}', '${escapeHtml(cita.estado)}', '${escapeHtml(cita.respuesta || '')}')">PDF</button>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            }

     
            function setupFormValidation() {
                const form = document.getElementById('citaForm');
                const fechaInput = document.getElementById('fechacita');
                const horaInput = document.getElementById('horacita');

                
                const today = new Date();
                const dd = String(today.getDate()).padStart(2, '0');
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const yyyy = today.getFullYear();
                const fechaHoy = yyyy + '-' + mm + '-' + dd;
                fechaInput.setAttribute('min', fechaHoy);

         
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    
                    if (!validateDateTime()) {
                        return;
                    }

           
                    const formData = {
                        fechacita: fechaInput.value,
                        horacita: horaInput.value,
                        tipocita: document.getElementById('tipocita').value,
                        apa: document.getElementById('apa').value,
                        estado: 'pendiente'
                    };

                    try {
                    
                        const response = await fetch(`${API_BASE_URL}/solicitud`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || 'Error al crear la cita');
                        }

                        const data = await response.json();
                        showAlert('Cita creada con éxito', 'success');

                        
                        generatePDF(
                            data.id,
                            formData.tipocita,
                            formData.fechacita,
                            formData.horacita,
                            formData.apa,
                            'pendiente',
                            ''
                        );


                        loadCitas();


                        form.reset();
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert(error.message, 'danger');
                    }
                });


                fechaInput.addEventListener('change', function() {
                    validateDate();
                });


                horaInput.addEventListener('change', function() {
                    validateTime();
                });
            }


            function validateDate() {
                const fechaInput = document.getElementById('fechacita');
                const fechaSeleccionada = new Date(fechaInput.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                fechaSeleccionada.setHours(0, 0, 0, 0);

                if (fechaSeleccionada < hoy) {
                    showAlert('No puedes seleccionar una fecha pasada', 'warning');

                    const dd = String(hoy.getDate()).padStart(2, '0');
                    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
                    const yyyy = hoy.getFullYear();
                    const fechaHoy = yyyy + '-' + mm + '-' + dd;
                    fechaInput.value = fechaHoy;
                    return false;
                }
                return true;
            }


            function validateTime() {
                const fechaInput = document.getElementById('fechacita');
                const horaInput = document.getElementById('horacita');
                const ahora = new Date();
                const fechaSeleccionada = new Date(fechaInput.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                fechaSeleccionada.setHours(0, 0, 0, 0);

      
                const hora = parseInt(horaInput.value.split(':')[0]);
                if (hora < 8 || hora >= 17) {
                    showAlert('El horario de atención es de 8:00 AM a 17:00 PM', 'warning');
                    horaInput.value = '08:00';
                    return false;
                }

                if (fechaSeleccionada.getTime() === hoy.getTime()) {
                    const [horaSel, minutoSel] = horaInput.value.split(':').map(Number);
                    const horaActual = ahora.getHours();
                    const minutoActual = ahora.getMinutes();

                    if (horaSel < horaActual || (horaSel === horaActual && minutoSel < minutoActual)) {
                        showAlert('No puedes agendar una cita en horario pasado para hoy', 'warning');

                        const nuevaHora = horaActual < 17 ? horaActual + 1 : 8;
                        horaInput.value = `${String(nuevaHora).padStart(2, '0')}:00`;
                        return false;
                    }
                }
                return true;
            }

            function validateDateTime() {
                return validateDate() && validateTime();
            }


            async function deleteCita(idcita) {
                if (!confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
                    return;
                }

                try {
                    const response = await fetch(`${API_BASE_URL}/${idcita}`, {
                        method: 'DELETE'
                    });

                    if (!response.ok) {
                        throw new Error('Error al eliminar la cita');
                    }

                    showAlert('Cita eliminada con éxito', 'success');
                    loadCitas();
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Error al eliminar la cita', 'danger');
                }
            }


            function generatePDF(id, tipo, fecha, hora, apa, estado, respuesta) {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

    
                const img = new Image();
                img.src = 'img/c.png';


                img.onload = function() {
                    // Encabezado
                    doc.addImage(img, 'PNG', 10, 10, 30, 30);
                    doc.setFontSize(20);
                    doc.setTextColor(40);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Comprobante de Cita', 105, 20, {
                        align: 'center'
                    });

 
                    doc.setFontSize(12);
                    doc.setTextColor(100);
                    doc.setFont('helvetica', 'normal');

                    const yStart = 40;
                    let y = yStart;

                    doc.text(`ID de Cita: ${id}`, 14, y);
                    y += 8;
                    doc.text(`Tipo: ${tipo}`, 14, y);
                    y += 8;
                    doc.text(`Fecha: ${formatDate(fecha)}`, 14, y);
                    y += 8;
                    doc.text(`Hora: ${hora}`, 14, y);
                    y += 8;
                    doc.text(`Apartamento: ${apa}`, 14, y);
                    y += 8;
                    doc.text(`Estado: ${estado}`, 14, y);
                    y += 8;

                    if (respuesta) {
                        doc.text(`Respuesta: ${respuesta}`, 14, y);
                        y += 8;
                    }


                    doc.setDrawColor(200);
                    doc.line(10, y, 200, y);
                    y += 10;


                    doc.setFontSize(10);
                    doc.text('Este documento sirve como comprobante de su cita solicitada tiene valor despues de haber recibido la confirmacion.', 14, y);
                    y += 5;
                    doc.text('Por favor presentarlo al llegar a su cita.', 14, y);
                    y += 5;
                    doc.text('SETS - Sistema de Gestión ', 105, y, {
                        align: 'center'
                    });

     
                    doc.save(`comprobante_cita_${id}.pdf`);
                };
            }

            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                const container = document.querySelector('.container');
                container.prepend(alertDiv);

                setTimeout(() => {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 150);
                }, 5000);
            }


            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }


            function getStatusBadgeClass(status) {
                switch (status.toLowerCase()) {
                    case 'pendiente':
                        return 'bg-warning text-dark';
                    case 'respondida':
                        return 'bg-success';
                    case 'cancelada':
                        return 'bg-danger';
                    default:
                        return 'bg-secondary';
                }
            }


            function escapeHtml(unsafe) {
                if (unsafe == null) return '';
                return unsafe.toString()
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }


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
        </script>
</body>
<br><br><br><br><br>
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