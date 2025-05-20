<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiculo = $_POST['vehiculo'];
    $PersonasIngreso = $_POST['PersonasIngreso'];
    $fechaHora = $_POST['fechaHora'];

    // Verificar si ya existe el registro
    $sql = "SELECT * FROM ingreso_vehicular WHERE vehiculo = :vehiculo AND fechaHora = :fechaHora LIMIT 1";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute(['vehiculo' => $vehiculo, 'fechaHora' => $fechaHora]); // Corrige los parámetros
    $Ingreso_Vehicular = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($Ingreso_Vehicular != false) {
        echo "<script>alert('La fecha ya se registró');</script>";
    } else {
        // Inserción de datos
        $sql = "INSERT INTO ingreso_vehicular (vehiculo, PersonasIngreso, fechaHora) VALUES (:vehiculo, :PersonasIngreso, :fechaHora)";
        $stmt = $base_de_datos->prepare($sql);

        // Ajusta los nombres de los parámetros
        if ($stmt->execute(['vehiculo' => $vehiculo, 'PersonasIngreso' => $PersonasIngreso, 'fechaHora' => $fechaHora])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error al solicitar el ingreso.";
        }
    }
}
?>
