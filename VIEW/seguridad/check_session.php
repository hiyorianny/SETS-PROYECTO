<?php
session_start();

if (!isset($_SESSION['id_Registro'])) {
    echo "ID de registro no está establecido en la sesión.";
    exit();
}

echo "ID de Registro en sesión: " . htmlspecialchars($_SESSION['id_Registro']);
?>
