<?php
include 'conexion.php';

// Configurar los encabezados para la respuesta
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM equipos";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute();
    
    // Obtener todos los equipos
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar respuesta en formato JSON
    echo json_encode($equipos);
} catch (PDOException $e) {
    // Manejo de errores en caso de falla en la consulta
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
