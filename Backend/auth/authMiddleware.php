<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "tu_clave_secreta";

function authenticate() {
    global $secret_key;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_COOKIE['token'])) {
        error_log("No se encontró token en las cookies");
        header("HTTP/1.1 401 Unauthorized");
        header("Location: http://localhost/SETS-PROYECTO/frontend-web/");
        exit();
    }

    $token = $_COOKIE['token'];

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        
        // Compatibilidad con ambos formatos de token
        if (isset($decoded->data)) {
            // Formato de login (con estructura data)
            $userData = $decoded->data;
        } else {
            // Formato de registro (sin estructura data)
            $userData = $decoded;
        }

        if (!isset($userData->idRol)) {
            error_log("Token no contiene idRol");
            header("HTTP/1.1 403 Forbidden");
            header("Location: http://localhost/SETS-PROYECTO/frontend-web/");
            exit();
        }

        // Almacenar en sesión
        $_SESSION['user_id'] = $userData->id ?? $userData->id_Registro;
        $_SESSION['user_role'] = $userData->idRol;
        $_SESSION['username'] = $userData->Usuario;

        return $decoded;
    } catch (Exception $e) {
        error_log("Error al decodificar token: " . $e->getMessage());
        header("HTTP/1.1 401 Unauthorized");
        header("Location: http://localhost/SETS-PROYECTO/frontend-web/");
        exit();
    }
}