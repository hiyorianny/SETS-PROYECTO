<?php  './src/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ciclista</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Nuevo Ciclista</h1>
        
        <form action="guardar.php" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                
                <div class="form-group">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" min="18" max="50" required>
                </div>
                
                <div class="form-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" required>
                </div>
                
                <div class="form-group">
                    <label for="especialidad">Especialidad:</label>
                    <select id="especialidad" name="especialidad" required>
                        <option value="">Seleccione...</option>
                        <option value="Contrarrelojista">Contrarrelojista</option>
                        <option value="Escalador">Escalador</option>
                        <option value="Sprinter">Sprinter</option>
                        <option value="Rodador">Rodador</option>
                        <option value="Gregario">Gregario</option>
                        <option value="Clasicómano">Clasicómano</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="salario">Salario Anual (USD):</label>
                    <input type="number" id="salario" name="salario" min="50000" step="0.01" required>
                </div>
            </div>
            
            <h2>Ficha Técnica</h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" id="peso" name="peso" min="40" max="100" step="0.1" required>
                </div>
                
                <div class="form-group">
                    <label for="altura">Altura (m):</label>
                    <input type="number" id="altura" name="altura" min="1.50" max="2.10" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="potencia_maxima">Potencia Máxima (W):</label>
                    <input type="number" id="potencia_maxima" name="potencia_maxima" min="200" max="600">
                </div>
                
                <div class="form-group">
                    <label for="vo2_max">VO2 Máximo (ml/kg/min):</label>
                    <input type="number" id="vo2_max" name="vo2_max" min="40" max="90" step="0.1">
                </div>
                
                <div class="form-group">
                    <label for="fecha_contrato">Fecha de Contrato:</label>
                    <input type="date" id="fecha_contrato" name="fecha_contrato" required>
                </div>
                
                <div class="form-group">
                    <label for="equipo_anterior">Equipo Anterior:</label>
                    <input type="text" id="equipo_anterior" name="equipo_anterior">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Guardar Ciclista</button>
                <a href="index.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
    
    <script>

        document.querySelector('form').addEventListener('submit', function(e) {
            const peso = parseFloat(document.getElementById('peso').value);
            const altura = parseFloat(document.getElementById('altura').value);
            const imc = peso / (altura * altura);
            
            if (imc < 18 || imc > 25) {
                if (!confirm('El IMC calculado está fuera del rango recomendado (18-25). ¿Deseas continuar?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>
