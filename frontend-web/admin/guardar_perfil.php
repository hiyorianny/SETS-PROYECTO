<?php
session_start();
include 'conexion.php'; 


if (!isset($_SESSION['id_Registro'])) {
    header('Location: login.php');
    exit();
}

$id_Registro = $_SESSION['id_Registro'];


$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$usuario = $_POST['usuario'];


$query = "UPDATE registro SET nombre = ?, apellido = ?, correo = ?, telefono = ?, usuario = ? WHERE id_Registro = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssi", $nombre, $apellido, $correo, $telefono, $usuario, $id_Registro);
if ($stmt->execute()) {
    echo "Perfil actualizado correctamente.";

} else {
    echo "Error al actualizar el perfil.";
}

$stmt->close();
$conn->close();
?>
