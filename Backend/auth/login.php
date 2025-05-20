<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Manejo de solicitudes OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'vendor/autoload.php';
use Firebase\JWT\JWT;

include_once "./conexion.php";

$secret_key = "tu_clave_secreta"; 

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['Usuario']) || empty($data['Clave'])) {
            throw new Exception("Usuario y contraseña son obligatorios.");
        }

        $Usuario = $data['Usuario'];
        $Clave = $data['Clave'];


        $sql = "SELECT r.id_Registro, r.Usuario, r.Clave, r.idRol, rol.Roldescripcion 
                FROM registro r
                LEFT JOIN rol ON r.idRol = rol.id
                WHERE r.Usuario = ?";
        $stmt = $base_de_datos->prepare($sql);
        if (!$stmt->execute([$Usuario])) {
            throw new Exception("Error en la consulta SQL");
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Usuario no encontrado.");
        }


        error_log("Datos usuario obtenidos: " . print_r($user, true));
        error_log("Rol obtenido: " . $user['idRol']);


        if (!password_verify($Clave, $user['Clave'])) {
            throw new Exception("Contraseña incorrecta.");
        }


        $sqlDelete = "DELETE FROM tokens WHERE id_Registro = ?";
        $stmtDelete = $base_de_datos->prepare($sqlDelete);
        $stmtDelete->execute([$user['id_Registro']]);


        $payload = [
            'iss' => "localhost",
            'aud' => "localhost",
            'iat' => time(),
            'exp' => time() + 4600,
            'data' => [
                'id_Registro' => $user['id_Registro'],
                'Usuario' => $user['Usuario'],
                'idRol' => $user['idRol'],
                'Roldescripcion' => $user['Roldescripcion']
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');


        $sqlInsert = "INSERT INTO tokens (id_Registro, token, fecha_expiracion) 
                     VALUES (?, ?, FROM_UNIXTIME(?))";
        $stmtInsert = $base_de_datos->prepare($sqlInsert);
        $stmtInsert->execute([
            $user['id_Registro'],
            $jwt,
            $payload['exp']
        ]);


        setcookie("token", $jwt, $payload['exp'], "/", "localhost", false, true);


        $redirectMap = [
            1111 => "1111", // admin
            2222 => "2222", // Guarda de Seguridad
            3333 => "3333", // residente

        ];

        $redirect = $redirectMap[$user['idRol']] ?? "error";

        error_log("Redirección determinada: " . $redirect);

        echo json_encode([
            'success' => true,
            'redirect' => $redirect, 
            'token' => $jwt,
            'rol' => $user['idRol'],
            'rolDesc' => $user['Roldescripcion']
        ]);
        exit;
    }
} catch (Exception $e) {
    error_log("Error en login: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    $base_de_datos = null;
}
