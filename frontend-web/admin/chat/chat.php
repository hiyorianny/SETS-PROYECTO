<?php
require __DIR__.'/conexion.php';
require __DIR__.'/../../../Backend/auth/controller/admin.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Obtener usuario autenticado
    $currentUserId = $_SESSION['user_id'] ?? null;
    $currentUserRole = $_SESSION['user_role'] ?? null;

    if (!$currentUserId) {
        throw new Exception('Usuario no autenticado', 401);
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    if (!isset($input['action'])) {
        throw new Exception('Acción no especificada');
    }

    // Procesar acciones
    switch ($input['action']) {
        case 'send':
            $content = trim($input['content'] ?? '');
            if (empty($content)) {
                throw new Exception('El mensaje no puede estar vacío');
            }

            $chatType = $input['chat_type'] ?? '';
            if (!in_array($chatType, ['privado', 'grupal'])) {
                throw new Exception('Tipo de chat no válido');
            }

            $receiverId = null;
            $groupChat = null;

            if ($chatType === 'privado') {
                $receiverId = $input['receiver_id'] ?? null;
                if (empty($receiverId)) {
                    throw new Exception('ID de destinatario no proporcionado');
                }

                // Verificar destinatario
                $stmt = $base_de_datos->prepare("SELECT id_Registro FROM registro WHERE id_Registro = ?");
                $stmt->execute([$receiverId]);
                if ($stmt->rowCount() === 0) {
                    throw new Exception('El usuario destinatario no existe');
                }
            } else {
                $groupChat = $input['group_chat'] ?? null;
                if (empty($groupChat)) {
                    throw new Exception('ID de grupo no proporcionado');
                }
            }

            // Insertar mensaje
            $stmt = $base_de_datos->prepare("INSERT INTO mensajes_chat 
                (id_remitente, id_destinatario, contenido, tipo_chat, grupo_chat, fecha_envio) 
                VALUES (?, ?, ?, ?, ?, NOW())");

            if (!$stmt->execute([$currentUserId, $receiverId, $content, $chatType, $groupChat])) {
                throw new Exception('Error al insertar mensaje');
            }

            $response = [
                'status' => 'success',
                'message' => 'Mensaje enviado',
                'message_id' => $base_de_datos->lastInsertId()
            ];
            break;

        case 'get_users':
            // Consulta mejorada para obtener usuarios
            $query = "SELECT r.id_Registro, r.PrimerNombre, r.PrimerApellido, r.Usuario, rol.Roldescripcion 
                      FROM registro r 
                      JOIN rol ON r.idRol = rol.id 
                      WHERE r.id_Registro != ? AND r.idRol != 4444"; // Excluir dueños

            $params = [$currentUserId];

            // Filtros por rol
            if ($currentUserRole == 2222) { // Guarda
                $query .= " AND r.idRol IN (1111, 3333)"; // Admin y residentes
            } elseif ($currentUserRole == 3333) { // Residente
                $query .= " AND r.idRol IN (1111, 2222)"; // Admin y guardas
            }

            $stmt = $base_de_datos->prepare($query);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = [
                'status' => 'success',
                'users' => $users,
                'groups' => [
                    ['id_Registro' => 'comunal', 'PrimerNombre' => 'Chat Comunal', 'Roldescripcion' => 'Grupo']
                ],
                'current_user_id' => $currentUserId
            ];
            break;

        case 'get_messages':
            if (!isset($input['chat_type']) || !isset($input['target_id'])) {
                throw new Exception('Parámetros incompletos');
            }

            $chatType = $input['chat_type'];
            $targetId = $input['target_id'];

            if (!in_array($chatType, ['privado', 'grupal'])) {
                throw new Exception('Tipo de chat no válido');
            }

            $query = "SELECT m.*, r.PrimerNombre, r.PrimerApellido, r.Usuario, rol.Roldescripcion 
                      FROM mensajes_chat m
                      JOIN registro r ON m.id_remitente = r.id_Registro
                      JOIN rol ON r.idRol = rol.id
                      WHERE ";
            
            $params = [];

            if ($chatType === 'privado') {
                $query .= "((m.id_remitente = ? AND m.id_destinatario = ?) OR 
                           (m.id_remitente = ? AND m.id_destinatario = ?))
                           AND m.tipo_chat = 'privado'
                           AND (m.eliminado_por_remitente = FALSE OR m.id_remitente != ?)
                           AND (m.eliminado_por_destinatario = FALSE OR m.id_destinatario != ?)";
                $params = [$currentUserId, $targetId, $targetId, $currentUserId, $currentUserId, $currentUserId];
            } else {
                $query .= "m.grupo_chat = ? AND m.tipo_chat = 'grupal'
                          AND (m.eliminado_por_remitente = FALSE OR m.id_remitente != ?)";
                $params = [$targetId, $currentUserId];
            }

            $query .= " ORDER BY m.fecha_envio ASC";

            $stmt = $base_de_datos->prepare($query);
            $stmt->execute($params);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = [
                'status' => 'success',
                'messages' => $messages,
                'current_user_id' => $currentUserId
            ];
            break;

        case 'delete_message':
            if (!isset($input['message_id'])) {
                throw new Exception('ID de mensaje no proporcionado');
            }

            $messageId = $input['message_id'];

            $stmt = $base_de_datos->prepare("SELECT id_remitente, id_destinatario, tipo_chat FROM mensajes_chat WHERE id_mensaje = ?");
            $stmt->execute([$messageId]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception('El mensaje no existe');
            }

            $messageData = $stmt->fetch(PDO::FETCH_ASSOC);

            $updateField = '';
            if ($messageData['id_remitente'] == $currentUserId) {
                $updateField = 'eliminado_por_remitente';
            } elseif ($messageData['id_destinatario'] == $currentUserId || $messageData['tipo_chat'] == 'grupal') {
                $updateField = 'eliminado_por_destinatario';
            } elseif ($currentUserRole == 1111) {
                $updateField = 'eliminado_por_remitente';
            } else {
                throw new Exception('No tienes permiso para eliminar este mensaje');
            }

            $stmt = $base_de_datos->prepare("UPDATE mensajes_chat SET $updateField = TRUE WHERE id_mensaje = ?");
            if (!$stmt->execute([$messageId])) {
                throw new Exception('Error al eliminar el mensaje');
            }

            $response = [
                'status' => 'success',
                'message' => 'Mensaje eliminado'
            ];
            break;

        default:
            throw new Exception('Acción no reconocida');
    }

} catch (PDOException $e) {
    $response = [
        'status' => 'error',
        'message' => 'Error de base de datos',
        'error_details' => $e->getMessage()
    ];
    http_response_code(500);
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    http_response_code($e->getCode() ?: 400);
}

ob_clean();
echo json_encode($response);
exit();