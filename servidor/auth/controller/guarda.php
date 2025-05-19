<?php
require __DIR__.'/../authMiddleware.php';

$decoded = authenticate();

$idRegistro = $decoded->id;
$Usuario = $decoded->Usuario;
$idRol = $decoded->idRol;

if ($idRol != 2222) {
    header("Location: http://localhost/sets/error.php");
    exit();
}


?>
