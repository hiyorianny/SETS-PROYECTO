<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sets - Pagos</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>
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
        <br> <br> <br>
        <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;">Insertar Pagos</div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-12 col-lg-10 mt-5">
                    <center>
                        <h2 class="mb-4">Historial de Pagos</h2>
                    </center>
                    <div class="table-container">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Concepto</th>
                                    <th scope="col">Monto</th>
                                    <th scope="col">Método</th>
                                    <th scope="col">Apartamento</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Referencia</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pagosTableBody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="container mt-5">
            <a href="perfil.php" class="btn btn-success">Volver</a>
        </div>
        </div>
        <center>
            </div>
            <br>
            </div>
            <br>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <script>
                const {
                    jsPDF
                } = window.jspdf;
                let pagosData = [];


                document.addEventListener('DOMContentLoaded', function() {
                    cargarPagos();
                    configurarFormulario();
                    configurarValidaciones();
                });


                function cargarPagos() {
                    fetch('http://192.168.1.102:3001/api/pagos')
                        .then(response => response.json())
                        .then(data => {
                            pagosData = data;
                            actualizarTablaPagos(data);
                        })
                        .catch(error => {
                            console.error('Error al cargar pagos:', error);
                            mostrarAlerta('Error al cargar los pagos', 'danger');
                        });
                }


                function actualizarTablaPagos(pagos) {
                    const tableBody = document.getElementById('pagosTableBody');
                    tableBody.innerHTML = '';

                    pagos.forEach(pago => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${pago.idPagos}</td>
                        <td>${pago.pagoPor}</td>
                        <td>${pago.cantidad}</td>
                        <td>${pago.mediopago}</td>
                        <td>${pago.apart}</td>
                        <td>${pago.fechaPago}</td>
                        <td>${pago.referenciaPago || '-'}</td>
                    
       
                        <td>
                            <button class="btn btn-danger delete-btn" data-id="${pago.idPagos}">Eliminar</button>
                            <button class="btn btn-success pdf-btn" data-id="${pago.idPagos}">PDF</button>
                        </td>
                    `;
                        tableBody.appendChild(row);
                    });


                    document.querySelectorAll('.estado-select').forEach(select => {
                        select.addEventListener('change', function() {
                            const idPago = this.getAttribute('data-id');
                            const nuevoEstado = this.value;
                            actualizarEstadoPago(idPago, nuevoEstado);
                        });
                    });


                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idPago = this.getAttribute('data-id');
                            eliminarPago(idPago);
                        });
                    });

                    document.querySelectorAll('.pdf-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idPago = this.getAttribute('data-id');
                            const pago = pagosData.find(p => p.idPagos == idPago);
                            generarPDF(pago);
                        });
                    });
                }


                function configurarFormulario() {
                    const form = document.getElementById('pagoForm');

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = {
                            pagoPor: document.getElementById('pagoPor').value,
                            cantidad: parseFloat(document.getElementById('cantidad').value),
                            mediopago: document.getElementById('mediopago').value,
                            apart: document.getElementById('apart').value,
                            fechaPago: document.getElementById('fechaPago').value,
                            estado: document.getElementById('estado').value,
                            referenciaPago: document.getElementById('referenciaPago').value || null
                        };


                        if (formData.cantidad <= 0) {
                            mostrarAlerta('La cantidad debe ser mayor a cero', 'danger');
                            return;
                        }


                        const hoy = new Date();
                        hoy.setHours(0, 0, 0, 0);
                        const fechaPago = new Date(formData.fechaPago);

                        if (fechaPago < hoy) {
                            mostrarAlerta('No puedes registrar pagos con fecha en el pasado', 'danger');
                            return;
                        }


                        fetch('http://192.168.1.102:3001/api/pagos', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(formData)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    throw new Error(data.error);
                                }
                                mostrarAlerta('Pago creado exitosamente', 'success');
                                form.reset();
                                cargarPagos();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                mostrarAlerta('Error al crear el pago: ' + error.message, 'danger');
                            });
                    });
                }

                function actualizarEstadoPago(idPago, nuevoEstado) {
                    fetch(`http://192.168.1.102:3001/api/pagos/${idPago}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                estado: nuevoEstado
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }
                            mostrarAlerta('Estado actualizado correctamente', 'success');
                            cargarPagos();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mostrarAlerta('Error al actualizar el estado: ' + error.message, 'danger');
                            cargarPagos();
                        });
                }


                function eliminarPago(idPago) {
                    if (!confirm('¿Estás seguro de que deseas eliminar este pago?')) {
                        return;
                    }

                    fetch(`http://192.168.1.102:3001/api/pagos/${idPago}`, {
                            method: 'DELETE'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }
                            mostrarAlerta('Pago eliminado correctamente', 'success');
                            cargarPagos();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mostrarAlerta('Error al eliminar el pago: ' + error.message, 'danger');
                        });
                }


                function generarPDF(pago) {
                    const doc = new jsPDF();


                    doc.addImage('img/c.png', 'PNG', 10, 10, 30, 30);


                    doc.setFontSize(20);
                    doc.text('COMPROBANTE DE PAGO', 105, 20, {
                        align: 'center'
                    });


                    doc.setFontSize(12);
                    doc.text(`ID Pago: ${pago.idPagos}`, 14, 50);
                    doc.text(`Concepto: ${pago.pagoPor}`, 14, 60);
                    doc.text(`Cantidad: $${pago.cantidad.toFixed(2)}`, 14, 70);
                    doc.text(`Medio de Pago: ${pago.mediopago}`, 14, 80);
                    doc.text(`Apartamento: ${pago.apart}`, 14, 90);
                    doc.text(`Fecha de Pago: ${pago.fechaPago}`, 14, 100);
                    if (pago.referenciaPago) {
                        doc.text(`Referencia: ${pago.referenciaPago}`, 14, 110);
                    }
                    doc.text(`Estado: ${pago.estado}`, 14, 120);


                    doc.text('_________________________', 50, 150);
                    doc.text('Firma del Administrador', 60, 160);


                    doc.save(`Pago_${pago.idPagos}_${pago.apart}.pdf`);
                }


                function mostrarAlerta(mensaje, tipo) {
                    const alerta = document.createElement('div');
                    alerta.className = `alert alert-${tipo} fixed-top text-center`;
                    alerta.style.marginTop = '80px';
                    alerta.style.zIndex = '1000';
                    alerta.textContent = mensaje;

                    document.body.appendChild(alerta);

                    setTimeout(() => {
                        alerta.remove();
                    }, 3000);
                }


                function configurarValidaciones() {
                    const fechaPagoInput = document.getElementById('fechaPago');
                    const cantidadInput = document.getElementById('cantidad');


                    const today = new Date();
                    const dd = String(today.getDate()).padStart(2, '0');
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const yyyy = today.getFullYear();
                    const fechaHoy = yyyy + '-' + mm + '-' + dd;
                    fechaPagoInput.setAttribute('min', fechaHoy);


                    fechaPagoInput.addEventListener('change', function() {
                        const fechaSeleccionada = new Date(this.value);
                        const hoy = new Date();
                        hoy.setHours(0, 0, 0, 0);

                        if (fechaSeleccionada < hoy) {
                            mostrarAlerta('No puedes seleccionar una fecha en el pasado', 'danger');
                            this.value = fechaHoy;
                        }
                    });


                    cantidadInput.addEventListener('change', function() {
                        if (parseFloat(this.value) <= 0) {
                            mostrarAlerta('La cantidad debe ser mayor a cero', 'danger');
                            this.value = '';
                        }
                    });
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
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

</body>

</html>