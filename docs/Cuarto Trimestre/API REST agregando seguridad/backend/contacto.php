<?php
include_once "conexion.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];


    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

       
        $sql = "INSERT INTO contacto (email) VALUES (:email)";
        $stmt = $base_de_datos->prepare($sql);

      
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $base_de_datos->errorInfo());
        }

 
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);


        if ($stmt->execute()) {
            echo "<script>
                    alert('Correo enviado con éxito.');
                    window.location.href = '../index.php'; // Redirige a la página principal
                  </script>";
        } else {
            echo "Error al enviar el correo: " . $stmt->errorInfo();
        }
    } else {
        echo "Por favor ingresa un correo válido.";
    }
}
?>
