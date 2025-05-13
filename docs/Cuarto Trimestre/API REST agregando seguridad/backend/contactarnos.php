<?php
include_once "conexion.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $comentario = $_POST['comentario'];

    if (!empty($nombre) && !empty($correo) && !empty($telefono) && !empty($comentario)) {


        $sql = "INSERT INTO contactarnos (nombre, correo, telefono, comentario) VALUES (:nombre, :correo, :telefono, :comentario)";
        $stmt = $base_de_datos->prepare($sql);


        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $base_de_datos->errorInfo());
        }

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);


        if ($stmt->execute()) {
            echo "<script>
                    alert('Contacto enviado con éxito.');
                    window.location.href = '../index.php'; // Redirige a la página principal u otra página
                  </script>";
        } else {
            echo "Error al enviar el contacto: " . $stmt->errorInfo();
        }
    } else {
        echo "Por favor completa todos los campos.";
    }
}
?>
