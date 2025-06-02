<?php
$host = 'sets.mysql.database.azure.com';
$contrasena = "Apartamento12";
$usuario = "wolwerine24";
$nombre_base_de_datos = "sets";

try {
    $base_de_datos = new PDO('mysql:host=' . $host . ';dbname=' . $nombre_base_de_datos, $usuario, $contrasena);

    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>
