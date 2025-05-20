<?php include_once '../src/database.php';
include_once '../src/models/CiclistaModel.php';

$model = new App\Models\CiclistaModel($base_de_datos);
$especialidad = $_GET['especialidad'] ?? null;
$ciclistas = $model->getAll($especialidad);
$totalPresupuesto = $model->getTotalPresupuesto(); ?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipo de Ciclistas</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="container">
        <h1>Equipo de Ciclistas</h1>
        
        <div class="header-actions">
            <a href="agregar.php" class="btn">Agregar Ciclista</a>
            <div class="total-presupuesto">
                <?php
                $stmt = $base_de_datos->query("SELECT COUNT(*) as total_ciclistas, SUM(salario) as total_salarios FROM ciclistas");
                $totales = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "Ciclistas: " . $totales['total_ciclistas'] . " | Presupuesto Total: $" . number_format($totales['total_salarios'], 2);
                ?>
            </div>
        </div>
        
        <div class="filtros">
            <form method="get" action="">
                <select name="especialidad" onchange="this.form.submit()">
                    <option value="">Todas las especialidades</option>
                    <?php
                    $especialidades = $base_de_datos->query("SELECT DISTINCT especialidad FROM ciclistas");
                    while ($esp = $especialidades->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($_GET['especialidad'] ?? '') == $esp['especialidad'] ? 'selected' : '';
                        echo "<option value='{$esp['especialidad']}' $selected>{$esp['especialidad']}</option>";
                    }
                    ?>
                </select>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>País</th>
                    <th>Especialidad</th>
                    <th>Salario</th>
                    <th>Ficha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $where = "";
                if (isset($_GET['especialidad']) && !empty($_GET['especialidad'])) {
                    $where = " WHERE especialidad = '" . $_GET['especialidad'] . "'";
                }
                
                $stmt = $base_de_datos->query("SELECT * FROM ciclistas $where ORDER BY nombre ASC");
                
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nombre']} {$row['apellido']}</td>
                            <td>{$row['edad']}</td>
                            <td>{$row['pais']}</td>
                            <td>{$row['especialidad']}</td>
                            <td>$" . number_format($row['salario'], 2) . "</td>
                            <td>
                                <a href='#' class='btn-ficha' onclick='mostrarFicha({$row['id']})'>Ver Ficha</a>
                                <div id='ficha-{$row['id']}' class='ficha-tecnica'>
                                    <h3>Ficha Técnica de {$row['nombre']} {$row['apellido']}</h3>
                                    <div class='ficha-grid'>
                                        <div><strong>Edad:</strong> {$row['edad']}</div>
                                        <div><strong>País:</strong> {$row['pais']}</div>
                                        <div><strong>Especialidad:</strong> {$row['especialidad']}</div>
                                        <div><strong>Salario:</strong> $" . number_format($row['salario'], 2) . "</div>
                                        <div><strong>Peso:</strong> {$row['peso']} kg</div>
                                        <div><strong>Altura:</strong> {$row['altura']} m</div>
                                        <div><strong>IMC:</strong> " . number_format($row['peso'] / ($row['altura'] * $row['altura']), 2) . "</div>
                                        <div><strong>Potencia Máx:</strong> " . ($row['potencia_maxima'] ?? 'N/A') . " W</div>
                                        <div><strong>VO2 Máx:</strong> " . ($row['vo2_max'] ?? 'N/A') . " ml/kg/min</div>
                                        <div><strong>Contrato desde:</strong> {$row['fecha_contrato']}</div>
                                        <div><strong>Equipo anterior:</strong> " . ($row['equipo_anterior'] ?? 'Ninguno') . "</div>
                                    </div>
                                    <button onclick='ocultarFicha({$row['id']})' class='btn'>Cerrar</button>
                                </div>
                            </td>
                            <td class='acciones'>
                                <a href='editar.php?id={$row['id']}' class='btn-editar'>Editar</a>
                                <a href='eliminar.php?id={$row['id']}' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de eliminar a {$row['nombre']} {$row['apellido']}?\")'>Eliminar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay ciclistas registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function mostrarFicha(id) {
            document.getElementById('ficha-' + id).style.display = 'block';
        }
        
        function ocultarFicha(id) {
            document.getElementById('ficha-' + id).style.display = 'none';
        }
    </script>
</body>
</html>
