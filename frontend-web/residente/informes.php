<?php
require __DIR__ . '/../../Backend/auth/controller/residente.php';

function getApiData($endpoint) {
    $apiUrl = 'http://192.168.1.102:3001'.$endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}


$anunciosData = getApiData('/api/anuncios');
$zonasComunesData = getApiData('/api/zonas-comunes');
$solicitudesZonasData = getApiData('/api/solicitudes-zonas');
$parqueaderosData = getApiData('/api/solicitudes-parqueadero');
$estadoParqueaderosData = getApiData('/api/solicitudes-parqueadero/estado');
$ingresosData = getApiData('/api/ingresos');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETS - Informes</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/principal.css?v=<?php echo (rand()); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 100%;
            margin: 20px auto;
            height: 300px;
        }
        .chart-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .chart-box {
            width: 48%;
            margin-bottom: 30px;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
           
        }
        h1 {
            text-align: center;
            color: #0e2c0a;
            margin: 30px 0;
        }
        h2 {
            text-align: center;
            color: #0e2c0a;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .chart-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid" style="background-color: #0e2c0a;">
                <img src="img/resi.png" alt="Logo" width="70" height="74" class="d-inline-block align-text-top" style="background-color: #0e2c0a;">

                <b style="font-size: 30px;color:aliceblue"> Residente - <?php echo htmlspecialchars($Usuario); ?> </b>
                </a>

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
                            <br>
                       
                            
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
    
 <main class="container" style="margin-top: 100px;">
    <h1>Reportes e Informes</h1>
    
    <div class="chart-row">
        <div class="chart-box">
            <h2>Anuncios Publicados (Últimos 6 meses)</h2>
            <div class="chart-container">
                <canvas id="anunciosChart"></canvas>
            </div>
        </div>
          <div class="chart-box">
            <h2>Solicitudes de Parqueaderos</h2>
            <div class="chart-container">
                <canvas id="solicitudesParqueaderoChart"></canvas>
            </div>
        </div>
    </div>
    <center>
    <div class="chart-row">
    
        
        <div class="chart-box">
            <h2>Estado Actual de Parqueaderos</h2>
            <div class="chart-container">
                <canvas id="estadoParqueaderosChart"></canvas>
            </div>
        </div>
    </div>
    </center>
<a href="./inicioprincipal.php" type="button" class="btn btn-success">Volver</a>
    <script>
        // Datos desde PHP
        const rawAnunciosData = <?php echo json_encode($anunciosData); ?>;
        const rawZonasComunesData = <?php echo json_encode($zonasComunesData); ?>;
        const rawSolicitudesZonasData = <?php echo json_encode($solicitudesZonasData); ?>;
        const rawParqueaderosData = <?php echo json_encode($parqueaderosData); ?>;
        const rawEstadoParqueaderosData = <?php echo json_encode($estadoParqueaderosData); ?>;
        const rawIngresosData = <?php echo json_encode($ingresosData); ?>;

        console.log('Datos de anuncios:', rawAnunciosData);
        console.log('Datos de zonas comunes:', rawZonasComunesData);
        console.log('Datos de solicitudes de zonas:', rawSolicitudesZonasData);
        console.log('Datos de parqueaderos:', rawParqueaderosData);
        console.log('Estado de parqueaderos:', rawEstadoParqueaderosData);

        // Procesamiento de datos para Anuncios
        function processAnunciosData(data) {
            if (!data || data.length === 0) {
                return {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    data: [0, 0, 0, 0, 0, 0]
                };
            }

            const last6Months = Array(6).fill(0);
            const monthNames = [];
            
            const currentDate = new Date();
            for (let i = 5; i >= 0; i--) {
                const date = new Date();
                date.setMonth(currentDate.getMonth() - i);
                const monthKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                monthNames.push(new Intl.DateTimeFormat('es-ES', { month: 'short' }).format(date));
                
                data.forEach(anuncio => {
                    if (anuncio.fechaPublicacion) {
                        const anuncioDate = new Date(anuncio.fechaPublicacion);
                        const anuncioMonth = `${anuncioDate.getFullYear()}-${String(anuncioDate.getMonth() + 1).padStart(2, '0')}`;
                        if (anuncioMonth === monthKey) {
                            last6Months[5 - i]++;
                        }
                    }
                });
            }
            
            return {
                labels: monthNames.map(name => name.charAt(0).toUpperCase() + name.slice(1)),
                data: last6Months
            };
        }

        // Procesamiento de datos para Solicitudes por Zona
       function processSolicitudesPorZona(zonasComunes, solicitudes) {
    // Verificar que tenemos datos
    if (!zonasComunes || zonasComunes.length === 0) {
        console.error('No hay datos de zonas comunes');
        return {
            labels: ['Sin datos de zonas'],
            data: [0],
            colors: ['rgba(200, 200, 200, 0.7)']
        };
    }

    if (!solicitudes || solicitudes.length === 0) {
        console.error('No hay datos de solicitudes');
        return {
            labels: zonasComunes.map(z => z.descripcion),
            data: zonasComunes.map(() => 0),
            colors: zonasComunes.map((_, i) => 
                `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`
            )
        };
    }

    // Contar solicitudes por zona
    const solicitudesPorZona = {};
    solicitudes.forEach(solicitud => {
        const zonaId = solicitud.ID_zonaComun;
        solicitudesPorZona[zonaId] = (solicitudesPorZona[zonaId] || 0) + 1;
    });

    // Preparar datos para el gráfico
    const labels = [];
    const data = [];
    const colors = [];
    
    zonasComunes.forEach(zona => {
        labels.push(zona.descripcion);
        data.push(solicitudesPorZona[zona.idZona] || 0);
        colors.push(getColorForZona(zona.idZona));
    });

    console.log('Datos procesados para gráfico:', { labels, data, colors });
    
    return {
        labels: labels,
        data: data,
        colors: colors
    };
}

// Función auxiliar para generar colores consistentes
function getColorForZona(zonaId) {
    const colores = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)'
    ];
    return colores[zonaId % colores.length];
}

        // Procesamiento de datos para Solicitudes de Parqueaderos
        function processSolicitudesParqueaderoData(data) {
            if (!data || data.length === 0) {
                return {
                    labels: ['Pendientes', 'Aprobadas', 'Rechazadas'],
                    data: [0, 0, 0],
                    colors: [
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ]
                };
            }

            const estados = {
                pendiente: { count: 0, color: 'rgb(255, 186, 11)' },
                aprobado: { count: 0, color: 'rgba(15, 77, 23, 0.7)' },
                rechazado: { count: 0, color: 'rgb(102, 8, 8)' }
            };
            
            data.forEach(item => {
                if (item.estado && estados[item.estado.toLowerCase()]) {
                    estados[item.estado.toLowerCase()].count++;
                }
            });
            
            return {
                labels: Object.keys(estados).map(e => e.charAt(0).toUpperCase() + e.slice(1)),
                data: Object.values(estados).map(e => e.count),
                colors: Object.values(estados).map(e => e.color)
            };
        }

        // Procesamiento de datos para Estado de Parqueaderos
        function processEstadoParqueaderosData(data) {
            if (!data || data.length === 0) {
                return {
                    labels: ['Disponibles', 'Ocupados', 'Reservados'],
                    data: [0, 0, 0],
                    colors: [
                        'rgba(13, 37, 15, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ]
                };
            }

            const estados = {
                disponible: { count: 0, color: 'rgba(13, 39, 20, 0.7)' },
                ocupado: { count: 0, color: 'rgba(255, 99, 132, 0.7)' },
                reservado: { count: 0, color: 'rgba(255, 206, 86, 0.7)' }
            };
            
            data.forEach(item => {
                if (item.estado && estados[item.estado.toLowerCase()]) {
                    estados[item.estado.toLowerCase()].count++;
                }
            });
            
            return {
                labels: Object.keys(estados).map(e => e.charAt(0).toUpperCase() + e.slice(1)),
                data: Object.values(estados).map(e => e.count),
                colors: Object.values(estados).map(e => e.color)
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
                // 1. Gráfico de Anuncios
                const anunciosProcessed = processAnunciosData(rawAnunciosData);
                new Chart(document.getElementById('anunciosChart'), {
                    type: 'bar',
                    data: {
                        labels: anunciosProcessed.labels,
                        datasets: [{
                            label: 'Anuncios',
                            data: anunciosProcessed.data,
                            backgroundColor: 'rgba(54, 235, 54, 0.7)',
                            borderColor: 'rgb(12, 34, 20)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                const parqueaderosProcessed = processSolicitudesParqueaderoData(rawParqueaderosData);
                new Chart(document.getElementById('solicitudesParqueaderoChart'), {
                    type: 'pie',
                    data: {
                        labels: parqueaderosProcessed.labels,
                        datasets: [{
                            data: parqueaderosProcessed.data,
                            backgroundColor: parqueaderosProcessed.colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // 4. Gráfico de Estado de Parqueaderos
                const estadoProcessed = processEstadoParqueaderosData(rawEstadoParqueaderosData);
                new Chart(document.getElementById('estadoParqueaderosChart'), {
                    type: 'bar',
                    data: {
                        labels: estadoProcessed.labels,
                        datasets: [{
                            label: 'Parqueaderos',
                            data: estadoProcessed.data,
                            backgroundColor: estadoProcessed.colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error al crear gráficos:', error);
            }
        });
    </script>
</main>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>