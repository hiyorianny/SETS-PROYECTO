<?php
include_once '../src/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $base_de_datos->prepare("SELECT * FROM ciclistas WHERE id = ?");
$stmt->execute([$id]);
$ciclista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ciclista) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ciclista</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Ciclista: <?php echo $ciclista['nombre'] . ' ' . $ciclista['apellido']; ?></h1>
        
        <form action="guardar.php" method="post">
            <input type="hidden" name="id" value="<?php echo $ciclista['id']; ?>">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $ciclista['nombre']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $ciclista['apellido']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" min="18" max="50" value="<?php echo $ciclista['edad']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" value="<?php echo $ciclista['pais']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="especialidad">Especialidad:</label>
                    <select id="especialidad" name="especialidad" required>
                        <option value="Contrarrelojista" <?php echo $ciclista['especialidad'] == 'Contrarrelojista' ? 'selected' : ''; ?>>Contrarrelojista</option>
                        <option value="Escalador" <?php echo $ciclista['especialidad'] == 'Escalador' ? 'selected' : ''; ?>>Escalador</option>
                        <option value="Sprinter" <?php echo $ciclista['especialidad'] == 'Sprinter' ? 'selected' : ''; ?>>Sprinter</option>
                        <option value="Rodador" <?php echo $ciclista['especialidad'] == 'Rodador' ? 'selected' : ''; ?>>Rodador</option>
                        <option value="Gregario" <?php echo $ciclista['especialidad'] == 'Gregario' ? 'selected' : ''; ?>>Gregario</option>
                        <option value="Clasicómano" <?php echo $ciclista['especialidad'] == 'Clasicómano' ? 'selected' : ''; ?>>Clasicómano</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="salario">Salario Anual (USD):</label>
                    <input type="number" id="salario" name="salario" min="50000" step="0.01" value="<?php echo $ciclista['salario']; ?>" required>
                </div>
            </div>
            
            <h2>Ficha Técnica</h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" id="peso" name="peso" min="40" max="100" step="0.1" value="<?php echo $ciclista['peso']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="altura">Altura (m):</label>
                    <input type="number" id="altura" name="altura" min="1.50" max="2.10" step="0.01" value="<?php echo $ciclista['altura']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="potencia_maxima">Potencia Máxima (W):</label>
                    <input type="number" id="potencia_maxima" name="potencia_maxima" min="200" max="600" value="<?php echo $ciclista['potencia_maxima']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="vo2_max">VO2 Máximo (ml/kg/min):</label>
                    <input type="number" id="vo2_max" name="vo2_max" min="40" max="90" step="0.1" value="<?php echo $ciclista['vo2_max']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="fecha_contrato">Fecha de Contrato:</label>
                    <input type="date" id="fecha_contrato" name="fecha_contrato" value="<?php echo $ciclista['fecha_contrato']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="equipo_anterior">Equipo Anterior:</label>
                    <input type="text" id="equipo_anterior" name="equipo_anterior" value="<?php echo $ciclista['equipo_anterior']; ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Actualizar Ciclista</button>
                <a href="index.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
    
    <script>

        const pesoInput = document.getElementById('peso');
        const alturaInput = document.getElementById('altura');
        
        function calcularIMC() {
            const peso = parseFloat(pesoInput.value);
            const altura = parseFloat(alturaInput.value);
            
            if (peso && altura) {
                const imc = peso / (altura * altura);
                console.log('IMC actualizado:', imc.toFixed(2));
            }
        }
        
        pesoInput.addEventListener('input', calcularIMC);
        alturaInput.addEventListener('input', calcularIMC);
    </script>
</body>
</html>
