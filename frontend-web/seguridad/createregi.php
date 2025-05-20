<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $personasIngreso = $_POST['personasIngreso'];
    $horaFecha = $_POST['horaFecha'];
    $documento = $_POST['documento'];
    $tipoIngreso = $_POST['tipo_ingreso'];
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;

    $sql = "SELECT * FROM ingreso_peatonal WHERE personasIngreso = :personasIngreso AND horaFecha = :horaFecha LIMIT 1";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute(['personasIngreso' => $personasIngreso, 'horaFecha' => $horaFecha]); 
    $Ingreso_Peatonal = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($Ingreso_Peatonal != false) {
        echo "<script>alert('La fecha ya se registró');</script>";
    } else {
        $sql = "INSERT INTO ingreso_peatonal (personasIngreso, horaFecha, documento, tipo_ingreso, placa) 
                VALUES (:personasIngreso, :horaFecha, :documento, :tipo_ingreso, :placa)";
        $stmt = $base_de_datos->prepare($sql);

        if ($stmt->execute([
            'personasIngreso' => $personasIngreso, 
            'horaFecha' => $horaFecha, 
            'documento' => $documento, 
            'tipo_ingreso' => $tipoIngreso, 
            'placa' => $placa
        ])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error al solicitar el ingreso.";
        }
    }
}
?>

