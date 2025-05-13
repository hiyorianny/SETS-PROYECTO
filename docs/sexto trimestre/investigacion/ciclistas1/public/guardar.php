<?php
include_once '../src/database.php';
include_once '../src/models/CiclistaModel.php';

$model = new App\Models\CiclistaModel($base_de_datos);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['edad'] < 18 || $_POST['edad'] > 50) {
        die("La edad debe estar entre 18 y 50 años");
    }
    
    if ($_POST['salario'] < 50000) {
        die("El salario mínimo es de $50,000");
    }


    $data = [
        ':nombre' => htmlspecialchars($_POST['nombre']),
        ':apellido' => htmlspecialchars($_POST['apellido']),
        ':edad' => intval($_POST['edad']),
        ':pais' => htmlspecialchars($_POST['pais']),
        ':especialidad' => htmlspecialchars($_POST['especialidad']),
        ':salario' => floatval($_POST['salario']),
        ':peso' => floatval($_POST['peso']),
        ':altura' => floatval($_POST['altura']),
        ':potencia_maxima' => isset($_POST['potencia_maxima']) ? intval($_POST['potencia_maxima']) : null,
        ':vo2_max' => isset($_POST['vo2_max']) ? floatval($_POST['vo2_max']) : null,
        ':fecha_contrato' => $_POST['fecha_contrato'],
        ':equipo_anterior' => isset($_POST['equipo_anterior']) ? htmlspecialchars($_POST['equipo_anterior']) : null
    ];
    
    try {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $success = $model->update(intval($_POST['id']), $data);
            $message = $success ? "Ciclista actualizado correctamente" : "Error al actualizar";
        } else {
            $success = $model->create($data);
            $message = $success ? "Ciclista creado correctamente" : "Error al crear";
        }
        
        header("Location: index.php?success=" . urlencode($message));
        exit();
    } catch (PDOException $e) {

        error_log("Error en guardar.php: " . $e->getMessage());
        

        die("Ocurrió un error al guardar los datos. Por favor intenta nuevamente.");
    }
} else {
    header("Location: index.php");
    exit();
}