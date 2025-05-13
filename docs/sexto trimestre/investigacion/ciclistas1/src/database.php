<?php
$host = 'localhost';
$contrasena = "";
$usuario = "root";
$nombre_base_de_datos = "equipo_ciclistas";

try {
    $base_de_datos = new PDO('mysql:host=' . $host . ';dbname=' . $nombre_base_de_datos, $usuario, $contrasena);

    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>
