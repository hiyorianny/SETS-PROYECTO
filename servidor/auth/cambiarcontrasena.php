<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include_once "conexion.php";

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['token']) || empty($data['nuevaContraseña'])) {
            throw new Exception("Token y nueva contraseña son obligatorios.");
        }

        $token = $data['token'];
        $nuevaContraseña = password_hash($data['nuevaContraseña'], PASSWORD_BCRYPT);

   
        $sql = "SELECT id_Registro FROM tokens WHERE token = ? AND fecha_expiracion > NOW()";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute([$token]);
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tokenData) {
            throw new Exception("Token inválido o expirado.");
        }

  
        $sqlUpdate = "UPDATE registro SET Clave = ? WHERE id_Registro = ?";
        $stmtUpdate = $base_de_datos->prepare($sqlUpdate);
        $stmtUpdate->execute([$nuevaContraseña, $tokenData['id_Registro']]);

        echo json_encode(['mensaje' => 'Contraseña actualizada correctamente.']);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    $base_de_datos = null;
}