<?php
include 'conexion.php';

// Configurar los encabezados para la respuesta
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM jugadores";
    $stmt = $base_de_datos->prepare($sql);
    $stmt->execute();
    
    // Obtener todos los jugadores
    $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar respuesta en formato JSON
    echo json_encode($jugadores);
} catch (PDOException $e) {
    // Manejo de errores en caso de falla en la consulta
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
