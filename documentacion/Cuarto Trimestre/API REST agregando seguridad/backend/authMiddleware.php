<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "tu_clave_secreta";

function authenticate() {
    global $secret_key;

    if (!isset($_COOKIE['token'])) {
        header("Location: http://localhost:3000/login");
        exit();
    }

    $token = $_COOKIE['token'];

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        header("Location: http://localhost:3000/login");
        exit();
    }
}
?>