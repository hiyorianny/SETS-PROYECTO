<?php
require __DIR__.'/../authMiddleware.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$decoded = authenticate();
error_log("Datos después de autenticar: " . print_r($decoded, true));
error_log("Datos de sesión: " . print_r($_SESSION, true));
if ($_SESSION['user_role'] != 1111) {
    error_log("Acceso denegado: Rol " . $_SESSION['user_role'] . " intentando acceder a página de seguridad");
    header("Location: http://localhost/SETS-PROYECTO/frontend-web/");
    exit();
}
$idRegistro = $_SESSION['user_id'];
$Usuario = $_SESSION['username'];
$idRol = $_SESSION['user_role'];


include_once "./conexion.php";
?>


