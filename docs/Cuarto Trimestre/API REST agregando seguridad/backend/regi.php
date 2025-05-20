<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

require 'vendor/autoload.php';
use Firebase\JWT\JWT;

include_once "conexion.php";

$secret_key = "tu_clave_secreta"; 

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $requiredFields = [
            'idRol', 'PrimerNombre', 'PrimerApellido', 
            'Correo', 'Id_tipoDocumento', 'numeroDocumento',
            'telefonoUno', 'Usuario', 'Clave'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo $field es obligatorio.");
            }
        }

        $idRol = $_POST['idRol'];
        
        // Campos específicos para roles que no son Guardia de Seguridad (2222)
        if ($idRol != 2222) {
            if (empty($_POST['tipo_propietario'])) {
                throw new Exception("El campo tipo_propietario es obligatorio para este rol.");
            }
            if (empty($_POST['apartamento'])) {
                throw new Exception("El campo apartamento es obligatorio para este rol.");
            }
        }


        $PrimerNombre = $_POST['PrimerNombre'];
        $SegundoNombre = $_POST['SegundoNombre'] ?? null;
        $PrimerApellido = $_POST['PrimerApellido'];
        $SegundoApellido = $_POST['SegundoApellido'] ?? null;
        $Correo = $_POST['Correo'];
        $tipo_propietario = $idRol != 2222 ? $_POST['tipo_propietario'] : null;
        $apartamento = $idRol != 2222 ? $_POST['apartamento'] : null;
        $Id_tipoDocumento = $_POST['Id_tipoDocumento'];
        $numeroDocumento = $_POST['numeroDocumento'];
        $telefonoUno = $_POST['telefonoUno'];
        $telefonoDos = $_POST['telefonoDos'] ?? null;
        $Usuario = $_POST['Usuario'];
        $Clave = $_POST['Clave'];

   
        if (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico no es válido.");
        }

       
        if (strlen($Clave) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres.");
        }

        $Clave = password_hash($Clave, PASSWORD_BCRYPT);

        $base_de_datos->beginTransaction();

        $sql = "INSERT INTO registro (idRol, PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, apartamento, Correo, Id_tipoDocumento, numeroDocumento, tipo_propietario, telefonoUno, telefonoDos, Usuario, Clave) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute([
            $idRol, $PrimerNombre, $SegundoNombre, $PrimerApellido, $SegundoApellido, 
            $apartamento, $Correo, $Id_tipoDocumento, $numeroDocumento, 
            $tipo_propietario, $telefonoUno, $telefonoDos, $Usuario, $Clave
        ]);

        $idRegistro = $base_de_datos->lastInsertId();

        // Generar token JWT
        $payload = [
            "id" => $idRegistro,
            "Usuario" => $Usuario,
            "Correo" => $Correo,
            "idRol" => $idRol,
            "exp" => time() + 3600 
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        $sqlToken = "INSERT INTO tokens (id_Registro, token, fecha_expiracion) VALUES (?, ?, ?)";
        $stmtToken = $base_de_datos->prepare($sqlToken);
        $fechaExpiracion = date('Y-m-d H:i:s', time() + 3600);
        $stmtToken->execute([$idRegistro, $jwt, $fechaExpiracion]);

        $base_de_datos->commit();

        setcookie("token", $jwt, time() + 3600, "/", "localhost", false, true);


        $redirectMap = [
            1111 => "1111", 
            2222 => "2222", 
            3333 => "3333"

        ];

        $redirect = $redirectMap[$idRol] ?? "error";

        echo json_encode([
            'success' => true,
            'redirect' => $redirect, 
            'token' => $jwt
        ]);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!isset($_GET['tipo'])) {
            throw new Exception("Parámetro 'tipo' no encontrado");
        }

        switch ($_GET['tipo']) {
            case 'roles':
                $result = $base_de_datos->query("SELECT id, Roldescripcion FROM rol");
                echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
                break;
                
            case 'tipodocs':
                $result = $base_de_datos->query("SELECT idtDoc, descripcionDoc FROM tipodoc");
                echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
                break;
                
            default:
                throw new Exception("Tipo no válido");
        }
    }
} catch (Exception $e) {
    if (isset($base_de_datos) && $base_de_datos->inTransaction()) {
        $base_de_datos->rollBack();
    }
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($base_de_datos)) {
        $base_de_datos = null;
    }
}