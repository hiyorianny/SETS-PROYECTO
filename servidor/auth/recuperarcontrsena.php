<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include_once "conexion.php";


require './vendor/autoload.php';


function enviarCorreoPHPMailer($destinatario, $token) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
     
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sets23434@gmail.com';
        $mail->Password = 'czvd zbqs gunh ekdo'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Remitente
        $mail->setFrom('sets23434@gmail.com', 'Sistema SETS');
        $mail->addReplyTo('no-reply@sets23434.com', 'No Responder');
        $mail->addAddress($destinatario);

        // Contenido
        $enlace = "http://localhost:3000/cambiar-contrasena?token=".urlencode($token);
        
        $mail->isHTML(true);
        $mail->Subject = 'Restablece tu contraseña en SETS';
        
        $mail->Body = "
          
            <h2>Restablecimiento de contraseña</h2>
            <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
            <p><a href='$enlace' style='background:rgb(24, 56, 28); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Restablecer contraseña</a></p>
            <p>Si no solicitaste este cambio, ignora este mensaje.</p>
            <p>El enlace expirará en 15 minutos.</p>
        ";
        
        $mail->AltBody = "Para restablecer tu contraseña, visita: $enlace";

        if(!$mail->send()) {
            error_log("Error enviando correo a $destinatario: " . $mail->ErrorInfo);
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Excepción al enviar correo: " . $e->getMessage());
        return false;
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['correo'])) {
            throw new Exception("El correo es obligatorio.");
        }

        $correo = filter_var($data['correo'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de correo inválido.");
        }

        
        $sql = "SELECT id_Registro FROM registro WHERE Correo = ?";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            $sqlToken = "SELECT token FROM tokens 
                        WHERE id_Registro = ? AND fecha_expiracion > NOW()";
            $stmtToken = $base_de_datos->prepare($sqlToken);
            $stmtToken->execute([$user['id_Registro']]);
            $tokenData = $stmtToken->fetch(PDO::FETCH_ASSOC);

            if (!$tokenData) {

                $token = bin2hex(random_bytes(32));
                $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));

                $sqlInsert = "INSERT INTO tokens (id_Registro, token, fecha_expiracion) 
                             VALUES (?, ?, ?)
                             ON DUPLICATE KEY UPDATE 
                             token = VALUES(token),
                             fecha_expiracion = VALUES(fecha_expiracion)";
                $stmtInsert = $base_de_datos->prepare($sqlInsert);
                $stmtInsert->execute([$user['id_Registro'], $token, $fecha_expiracion]);
            } else {
                $token = $tokenData['token'];
            }

         
            if (enviarCorreoPHPMailer($correo, $token)) {
                echo json_encode([
                    'mensaje' => 'Se ha enviado un enlace de recuperación a tu correo.'
                ]);
            } else {
                throw new Exception("Error al enviar el correo. Por favor, inténtalo más tarde.");
            }
        } else {
            
            echo json_encode([
                'mensaje' => 'Si el correo existe, recibirás un enlace de recuperación.'
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    $base_de_datos = null;
}