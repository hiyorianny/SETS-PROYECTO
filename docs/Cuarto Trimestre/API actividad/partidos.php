<?php
include 'conexion.php';

$sql = "SELECT * FROM partidos";
$stmt = $base_de_datos->prepare($sql);
$stmt->execute();
$partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($partidos);
?>
