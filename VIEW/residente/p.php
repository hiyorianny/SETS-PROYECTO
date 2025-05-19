<?php
// Conectar a la base de datos
include_once "conexion.php";
session_start(); // Asegúrate de que la sesión esté iniciada


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $torreId = $_POST['torre'];
    $pisoId = $_POST['piso'];
    $apartamentoId = $_POST['apartamento'];

    try {
        // Verificar si el registroId existe en la tabla registro
        // Insertar en la tabla piso_apto
        $stmt = $base_de_datos->prepare("INSERT INTO apartamento (id_Apartamento, numApartamento) VALUES (:pisoId, :apartamentoId)");
        $stmt->bindParam(':pisoId', $pisoId);
        $stmt->bindParam(':apartamentoId', $apartamentoId);
        $stmt->execute();



        // Insertar en la tabla torre_piso
        $stmt = $base_de_datos->prepare("INSERT INTO apartamento (pisoid, Torreid) VALUES (:pisoId, :torreId)");
        $stmt->bindParam(':pisoId', $pisoId);
        $stmt->bindParam(':torreId', $torreId);
        $stmt->execute();

        echo "Datos insertados correctamente.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
