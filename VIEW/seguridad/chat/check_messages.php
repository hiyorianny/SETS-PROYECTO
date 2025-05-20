<?php
require '../../../MODEL/backend/authMiddleware.php';
include_once "conexion.php";

$decoded = authenticate();
$idUsuario = $decoded->id;

// Consulta para mensajes no leídos
$sql = "SELECT COUNT(*) as count FROM mensajes_chat 
        WHERE id_destinatario = :id_usuario 
        AND fecha_envio > (SELECT last_check FROM usuarios WHERE id = :id_usuario)";
$stmt = $base_de_datos->prepare($sql);
$stmt->bindParam(':id_usuario', $idUsuario);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['newMessages' => $result['count'] > 0]);

// Actualizar el last_check del usuario
$updateSql = "UPDATE usuarios SET last_check = NOW() WHERE id = :id_usuario";
$updateStmt = $base_de_datos->prepare($updateSql);
$updateStmt->bindParam(':id_usuario', $idUsuario);
$updateStmt->execute();
?>