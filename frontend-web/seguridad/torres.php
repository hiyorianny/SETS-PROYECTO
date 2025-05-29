<?php
require __DIR__ . '/../../Backend/auth/controller/guarda.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SETS - Pisos</title>
  <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
  <link rel="stylesheet" href="css/pisos.css?v=<?php echo (rand()); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
  <br>
  <br>
  <br>
  <br>
  <br>
  <div class="alert alert-success" role="alert" style="font-size: 40px; text-align:center">
    <b>Torre Pisos y Apartamentos</b>
  </div>
  <div class="container">
    <div class="barra">
      <div class="sombra"></div>
      <input type="text" id="buscarPiso" placeholder="Buscar Piso..." onkeyup="filtrarPisos()">
      <ion-icon name="search-outline"></ion-icon>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <br>
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between">
          <button class="btn btn-outline-success" onclick="cambiarTorre(-1)">← Anterior</button>
          <h2 id="torreActual" style="text-align: center;">Cargando torres...</h2>
          <button class="btn btn-outline-success" onclick="cambiarTorre(1)">Siguiente →</button>
        </div>
        <div id="contenidoTorre">

          <div class="text-center">
            <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando datos de torres y apartamentos...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <a href="./ingresoregi.php" class="btn btn-outline-success" style="font-size: 30px;">Ingreso Peatonal</a>
  </div>
  <a href="inicioprincipal.php" class="btn btn-outline-success" style="font-size: 30px;">Volver</a>
  <script>
    let torresData = {};
    let torresList = [];
    let torreActual = 1;
    let torresCargadas = false;

    async function cargarDatosTorres() {
      try {
        const response = await fetch('http://192.168.1.102:3001/api/torres');
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        const data = await response.json();
        console.log('Datos recibidos:', data);
        if (!data || !data.torres) {
          throw new Error('La API no devolvió datos en el formato esperado');
        }
        torresData = data.torres;
        torresList = [...new Set(data.torresList || Object.keys(data.torres))]
          .map(torre => {

            const num = torre.replace(/\D/g, '');
            return num ? parseInt(num) : 0;
          })
          .filter(num => num > 0)
          .sort((a, b) => a - b);
        torresList = [...new Set(torresList)];

        torresCargadas = true;

        if (torresList.length > 0) {
          torreActual = torresList[0];
          mostrarTorre(torreActual.toString());
          actualizarTituloTorre();
        } else {
          mostrarEstado('No se encontraron torres registradas.', 'warning');
        }
      } catch (error) {
        console.error('Error:', error);
        mostrarEstado(`Error al cargar los datos: ${error.message}`, 'error');
      }
    }

    function mostrarEstado(mensaje, tipo = 'info') {
      const contenido = document.getElementById('contenidoTorre');
      const alertClass = tipo === 'error' ? 'alert-danger' : 'alert-warning';

      contenido.innerHTML = `
      <div class="alert ${alertClass}" role="alert">
        ${mensaje}
      </div>
    `;
    }

    function mostrarTorre(torre) {
      if (!torresCargadas) return;

      const contenido = document.getElementById('contenidoTorre');

      const torreKey = Object.keys(torresData).find(key => key.startsWith(torre)) || torre;
      const torreInfo = torresData[torreKey];

      if (!torreInfo) {
        mostrarEstado(`No se encontró información para la torre ${torre}.`, 'warning');
        return;
      }

      let html = '';


      const pisos = Object.keys(torreInfo).sort((a, b) => {
        const numA = parseInt(a.replace(/\D/g, '')) || 0;
        const numB = parseInt(b.replace(/\D/g, '')) || 0;
        return numA - numB;
      });

      pisos.forEach(piso => {
        const apartamentos = torreInfo[piso];

        if (!Array.isArray(apartamentos)) {
          console.warn(`Piso ${piso} no tiene array de apartamentos`);
          return;
        }

        html += `
        <div class="card shadow mb-4">
          <div class="card-header bg-success text-white">
            <h2 class="mb-0" style="text-align: center;"><b>Piso: ${piso}</b></h2>
          </div>
          <div class="card-body">
            <h4 style="text-align: center;"><b>Apartamentos:</b></h4>
            <div class="row">
      `;

        apartamentos.forEach(apartamento => {
          const numApto = apartamento.numApartamento || 'N/A';
          html += `
          <div class="col-md-6 mb-3">
            <div class="card card-apartamento">
              <div class="card-body">
                <strong>Número:</strong> ${numApto}<br>
              </div>
            </div>
          </div>
        `;
        });

        html += `
            </div>
          </div>
        </div>
      `;
      });

      contenido.innerHTML = html || `
      <div class="alert alert-warning">
        No se encontraron datos para mostrar en esta torre
      </div>
    `;
    }

    function actualizarTituloTorre() {
      document.getElementById('torreActual').textContent = `Torre ${torreActual}`;
    }

    function cambiarTorre(direccion) {
      if (!torresCargadas || torresList.length === 0) return;

      const indiceActual = torresList.indexOf(torreActual);
      let nuevoIndice = indiceActual + direccion;


      if (nuevoIndice < 0) {
        nuevoIndice = torresList.length - 1;
      } else if (nuevoIndice >= torresList.length) {
        nuevoIndice = 0;
      }

      torreActual = torresList[nuevoIndice];
      mostrarTorre(torreActual.toString());
      actualizarTituloTorre();
    }

    function filtrarPisos() {
      if (!torresCargadas) return;

      const busqueda = document.getElementById('buscarPiso').value.toLowerCase();
      const cards = document.querySelectorAll('.card.shadow.mb-4');

      cards.forEach(card => {
        const pisoText = card.querySelector('.card-header h2').textContent.toLowerCase();
        const apartamentos = card.querySelectorAll('.card.card-apartamento');
        let mostrarCard = pisoText.includes(busqueda);

        if (!mostrarCard) {
          apartamentos.forEach(apto => {
            if (apto.textContent.toLowerCase().includes(busqueda)) {
              mostrarCard = true;
            }
          });
        }

        card.style.display = mostrarCard ? 'block' : 'none';
      });
    }

    document.addEventListener('DOMContentLoaded', cargarDatosTorres);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <br>
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
</body>

</html>