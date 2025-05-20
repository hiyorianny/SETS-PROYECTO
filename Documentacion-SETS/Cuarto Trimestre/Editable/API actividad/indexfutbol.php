<?php
header('Access-Control-Allow-Origin: *');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFA API</title>
    <link rel="stylesheet" href="style.css?v=<?php echo (rand()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="10.jpg" alt="Logo" width="100" height="104" class="d-inline-block align-text-top">
    </a>
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Equipos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Jugadores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Partidos</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<br>
<div class="alert alert-primary text-center" role="alert">
  <img src="10.jpg" alt="Logo" width="200" height="204" class="d-inline-block align-text-top">
  <br>
  <b>FIFA API</b>
</div>

<!-- Buscador -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <input type="text" id="search-equipos" class="form-control" placeholder="Buscar equipos...">
        </div>
        <div class="col-md-4 mx-auto">
            <input type="text" id="search-jugadores" class="form-control" placeholder="Buscar jugadores...">
        </div>
    </div>
</div>

<!-- Lista de Equipos -->
<div class="container mt-4">
    <h2>Lista de Equipos</h2>
    <div id="loading-equipos" class="text-center">Cargando equipos...</div>
    <ul id="equipos-list" class="list-group">
        <!-- Equipos se cargarán aquí -->
    </ul>
</div>

<!-- Lista de Jugadores -->
<div class="container mt-4">
    <h2>Lista de Jugadores</h2>
    <div id="loading-jugadores" class="text-center">Cargando jugadores...</div>
    <ul id="jugadores-list" class="list-group">
        <!-- Jugadores se cargarán aquí -->
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchEquipos = document.getElementById('search-equipos');
    const searchJugadores = document.getElementById('search-jugadores');
    const equiposList = document.getElementById('equipos-list');
    const jugadoresList = document.getElementById('jugadores-list');
    const loadingEquipos = document.getElementById('loading-equipos');
    const loadingJugadores = document.getElementById('loading-jugadores');

    // Fetch Equipos
    fetch('http://localhost/api/equipos.php')
        .then(response => response.json())
        .then(data => {
            loadingEquipos.style.display = 'none';
            renderEquipos(data);
        })
        .catch(error => {
            console.error('Error al cargar los equipos:', error);
            loadingEquipos.innerHTML = '<li class="list-group-item text-danger">Error al cargar los equipos</li>';
        });

    // Fetch Jugadores
    fetch('http://localhost/api/jugadores.php')
        .then(response => response.json())
        .then(data => {
            loadingJugadores.style.display = 'none';
            renderJugadores(data);
        })
        .catch(error => {
            console.error('Error al cargar los jugadores:', error);
            loadingJugadores.innerHTML = '<li class="list-group-item text-danger">Error al cargar los jugadores</li>';
        });

    // Render Equipos
    function renderEquipos(equipos) {
        equiposList.innerHTML = '';
        if (equipos.length === 0) {
            equiposList.innerHTML = '<li class="list-group-item">No se encontraron equipos</li>';
        } else {
            equipos.forEach(equipo => {
                const li = document.createElement('li');
                li.classList.add('list-group-item');
                li.textContent = `${equipo.nombre} - ${equipo.ciudad}`;
                equiposList.appendChild(li);
            });
        }
    }

    // Render Jugadores
    function renderJugadores(jugadores) {
        jugadoresList.innerHTML = '';
        if (jugadores.length === 0) {
            jugadoresList.innerHTML = '<li class="list-group-item">No se encontraron jugadores</li>';
        } else {
            jugadores.forEach(jugador => {
                const li = document.createElement('li');
                li.classList.add('list-group-item');
                li.textContent = `${jugador.nombre} - ${jugador.posicion} -${jugador.goles} `;
                jugadoresList.appendChild(li);
            });
        }
    }

    // Búsqueda para Equipos
    searchEquipos.addEventListener('input', function() {
        const searchText = searchEquipos.value.toLowerCase();
        const filteredEquipos = Array.from(equiposList.children).filter(li => 
            li.textContent.toLowerCase().includes(searchText)
        );
        equiposList.innerHTML = '';
        filteredEquipos.forEach(li => equiposList.appendChild(li));
    });

    // Búsqueda para Jugadores
    searchJugadores.addEventListener('input', function() {
        const searchText = searchJugadores.value.toLowerCase();
        const filteredJugadores = Array.from(jugadoresList.children).filter(li => 
            li.textContent.toLowerCase().includes(searchText)
        );
        jugadoresList.innerHTML = '';
        filteredJugadores.forEach(li => jugadoresList.appendChild(li));
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
