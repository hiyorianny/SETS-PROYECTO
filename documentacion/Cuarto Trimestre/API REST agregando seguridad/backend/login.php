<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require 'vendor/autoload.php';
use Firebase\JWT\JWT;

include_once "conexion.php";

$secret_key = "tu_clave_secreta"; 

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validar datos de entrada
        if (empty($data['Usuario']) || empty($data['Clave'])) {
            throw new Exception("Usuario y contraseña son obligatorios.");
        }

        $Usuario = $data['Usuario'];
        $Clave = $data['Clave'];

        // Buscar el usuario 
        $sql = "SELECT id_Registro, Usuario, Clave, idRol FROM registro WHERE Usuario = ?";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute([$Usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Usuario no encontrado.");
        }

        // Verificar 
        if (!password_verify($Clave, $user['Clave'])) {
            throw new Exception("Contraseña incorrecta.");
        }

       
        $sqlToken = "SELECT token FROM tokens WHERE id_Registro = ? AND fecha_expiracion > NOW()";
        $stmtToken = $base_de_datos->prepare($sqlToken);
        $stmtToken->execute([$user['id_Registro']]);
        $tokenData = $stmtToken->fetch(PDO::FETCH_ASSOC);

        if (!$tokenData) {
            throw new Exception("No se encontró un token válido para este usuario.");
        }

        $jwt = $tokenData['token'];

        // Configurar la cookie en la respuesta
        setcookie("token", $jwt, time() + 3900, "/", "localhost", false, true);

        // Determinar la redirección según el rol
        $redirect = "";
        switch ($user['idRol']) {
            case 1111:
                $redirect = "1111"; 
                break;
            case 2222:
                $redirect = "2222"; 
                break;
            case 3333:
                $redirect = "3333"; 
                break;
            case 4444:
                $redirect = "4444"; 
                break;
            default:
                $redirect = "error";
                break;
        }


        echo json_encode(['redirect' => $redirect, 'token' => $jwt]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {

    $base_de_datos = null;
}