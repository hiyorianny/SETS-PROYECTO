<?php
require __DIR__ . '/../../Backend/auth/controller/admin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sets - Ingreso Peatonal</title>
  <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
  <link rel="stylesheet" href="css/citasFormulario.css?v=<?php echo (rand()); ?>">
</head>
</head>

<body>
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
  <main>
    <center>
      <br> <br> <br>
      <div class="alert alert-success" role="alert" style="text-align: center; font-size :30px;">Ingreso Peatonal y Vehicular </div>
      <div class="row justify-content-center"> 
        <div class="col-sm-12 col-md-10 col-lg-10 mt-5"> 
          <center>
            <h2>Panel de Ingresos</h2>
          </center>
          <br>
          <div class="table-responsive"> 
            <table class="table table-bordered"> 
              <thead>
                <tr>
                  <th scope="col">idIngreso</th>
                  <th scope="col">Nombre de Persona de Ingreso</th>
                  <th scope="col">fecha y Hora</th>
                  <th scope="col">Tipo y Numero de Documento</th>
                  <th scope="col">Placa del Vehiculo</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody id="tablaIngresos">
                <tr>
                  <td colspan="6" class="text-center">
                    <div class="spinner-border text-success" role="status">
                      <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando datos de ingresos...</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <br>

      <div class="container mt-5">
        <a href="torres.php" class="btn btn-success">Volver</a>
      </div>
      </div>
    </center>
  </main>
  <script>
    async function cargarIngresos() {
      try {
        const response = await fetch('http://192.168.1.102:3001/api/ingresos');
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        const ingresos = await response.json();

        const tabla = document.getElementById('tablaIngresos');
        tabla.innerHTML = '';

        if (ingresos.length === 0) {
          tabla.innerHTML = '<tr><td colspan="6" class="text-center">No hay ingresos registrados</td></tr>';
          return;
        }

        ingresos.forEach(ingreso => {
          const fila = document.createElement('tr');
          fila.innerHTML = `
                        <td>${ingreso.idIngreso_Peatonal}</td>
                        <td>${ingreso.personasIngreso}</td>
                        <td>${ingreso.horaFecha}</td>
                        <td>${ingreso.documento}</td>
                        <td>${ingreso.placa || '-'}</td>
                        <td>
                            <button class="btn btn-danger mt-3" onclick="eliminarIngreso(${ingreso.idIngreso_Peatonal})">Eliminar</button>
                        </td>
                    `;
          tabla.appendChild(fila);
        });
      } catch (error) {
        console.error('Error:', error);
        document.getElementById('tablaIngresos').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Error al cargar los datos: ${error.message}
                            <button onclick="cargarIngresos()" class="btn btn-sm btn-warning">Reintentar</button>
                        </td>
                    </tr>
                `;
      }
    }
    async function guardarIngreso(event) {
      event.preventDefault();

      const form = event.target;
      const formData = new FormData(form);
      const data = {
        tipo_ingreso: formData.get('tipo_ingreso'),
        placa: formData.get('placa'),
        personasIngreso: formData.get('personasIngreso'),
        documento: formData.get('documento'),
        horaFecha: formData.get('horaFecha')
      };
      const fechaSeleccionada = new Date(data.horaFecha);
      const ahora = new Date();
      if (fechaSeleccionada < ahora) {
        alert('No puedes registrar ingresos con fecha/hora en el pasado');
        return;
      }

      try {
        const response = await fetch('http://192.168.1.102:3001/api/ingresos', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        if (!response.ok) {
          throw new Error('Error al registrar el ingreso');
        }

        const result = await response.json();
        alert('Ingreso registrado exitosamente');
        form.reset();
        cargarIngresos();
      } catch (error) {
        console.error('Error:', error);
        alert(`Error al registrar el ingreso: ${error.message}`);
      }
    }


    async function eliminarIngreso(id) {
      if (!confirm('¿Estás seguro de que deseas eliminar este ingreso?')) {
        return;
      }

      try {
        const response = await fetch(`http://192.168.1.102:3001/api/ingresos/${id}`, {
          method: 'DELETE'
        });

        if (!response.ok) {
          throw new Error('Error al eliminar el ingreso');
        }

        alert('Ingreso eliminado exitosamente');
        cargarIngresos();
      } catch (error) {
        console.error('Error:', error);
        alert(`Error al eliminar el ingreso: ${error.message}`);
      }
    }


    function togglePlaca() {
      var tipoIngreso = document.getElementById("tipo_ingreso").value;
      var placaContainer = document.getElementById("placaContainer");

      if (tipoIngreso === "vehiculo") {
        placaContainer.style.display = "block";
        document.getElementById("placa").setAttribute("required", "required");
      } else {
        placaContainer.style.display = "none";
        document.getElementById("placa").removeAttribute("required");
        document.getElementById("placa").value = "";
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      cargarIngresos();
      const fechaHoraInput = document.getElementById('horaFecha');
      const now = new Date();
      const timezoneOffset = now.getTimezoneOffset() * 60000;
      const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);
      fechaHoraInput.min = localISOTime;
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
<br>
<br>
<br>
<br>
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

</html>

</body>

</html>