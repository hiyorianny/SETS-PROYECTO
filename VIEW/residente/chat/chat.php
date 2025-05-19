<?php
require './conexion.php';
require '../../../MODEL/backend/authMiddleware.php';

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Responder a OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Autenticar al usuario
    $decoded = authenticate();
    $response = ['status' => 'error', 'message' => 'Acción no válida'];

    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Obtener datos de entrada
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('Datos de solicitud vacíos');
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    if (!isset($data['action'])) {
        throw new Exception('Acción no especificada');
    }

    // Procesar acciones
    switch ($data['action']) {
        case 'send':
            $content = trim($data['content']);
            if (empty($content)) {
                throw new Exception('El mensaje no puede estar vacío');
            }

            if (!isset($data['chat_type'])) {
                throw new Exception('Tipo de chat no especificado');
            }

            $chatType = $data['chat_type'];
            if (!in_array($chatType, ['privado', 'grupal'])) {
                throw new Exception('Tipo de chat no válido');
            }

            $senderId = $decoded->id;
            $receiverId = null;
            $groupChat = null;

            if ($chatType === 'privado') {
                if (!isset($data['receiver_id'])) {
                    throw new Exception('ID de destinatario no proporcionado');
                }

                $receiverId = $data['receiver_id'];
                if (empty($receiverId)) {
                    throw new Exception('ID de destinatario no puede estar vacío');
                }

                // Verificar destinatario
                $stmtCheck = $base_de_datos->prepare("SELECT id_Registro FROM registro WHERE id_Registro = ?");
                $stmtCheck->execute([$receiverId]);
                if ($stmtCheck->rowCount() === 0) {
                    throw new Exception('El usuario destinatario no existe');
                }
            } else {
                if (!isset($data['group_chat'])) {
                    throw new Exception('ID de grupo no proporcionado');
                }
                $groupChat = $data['group_chat'];
            }

            // Insertar mensaje
            $stmt = $base_de_datos->prepare("INSERT INTO mensajes_chat 
                (id_remitente, id_destinatario, contenido, tipo_chat, grupo_chat, fecha_envio) 
                VALUES (?, ?, ?, ?, ?, NOW())");

            if (!$stmt->execute([$senderId, $receiverId, $content, $chatType, $groupChat])) {
                throw new Exception('Error al insertar mensaje');
            }

            $response = [
                'status' => 'success',
                'message' => 'Mensaje enviado',
                'message_id' => $base_de_datos->lastInsertId()
            ];
            break;

        case 'get_users':
            $currentUserId = $decoded->id;
            $currentUserRole = $decoded->idRol;

            // Consulta mejorada para obtener usuarios
            $query = "SELECT r.id_Registro, r.PrimerNombre, r.PrimerApellido, r.Usuario, rol.Roldescripcion 
                      FROM registro r 
                      JOIN rol ON r.idRol = rol.id 
                      WHERE r.id_Registro != ? AND r.idRol != 4444"; // Excluir dueños

            // Filtros por rol
            $params = [$currentUserId];

            if ($currentUserRole == 1111) { // Admin
                // Puede hablar con todos
            } elseif ($currentUserRole == 2222) { // Guarda
                $query .= " AND r.idRol IN (1111, 3333)"; // Admin y residentes
            } elseif ($currentUserRole == 3333) { // Residente
                $query .= " AND r.idRol IN (1111, 2222)"; // Admin y guardas
            }

            $stmt = $base_de_datos->prepare($query);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Grupos disponibles
            $groups = [
                ['id_Registro' => 'comunal', 'PrimerNombre' => 'Chat Comunal', 'Roldescripcion' => 'Grupo']
            ];

            $response = [
                'status' => 'success',
                'users' => $users,
                'groups' => $groups,
                'current_user_id' => $currentUserId
            ];
            break;
        case 'delete_message':
            if (!isset($data['message_id'])) {
                throw new Exception('ID de mensaje no proporcionado');
            }

            $messageId = $data['message_id'];
            $currentUserId = $decoded->id;

            // Obtener información del mensaje
            $stmtCheck = $base_de_datos->prepare("SELECT id_remitente, id_destinatario, tipo_chat FROM mensajes_chat WHERE id_mensaje = ?");
            $stmtCheck->execute([$messageId]);

            if ($stmtCheck->rowCount() === 0) {
                throw new Exception('El mensaje no existe');
            }

            $messageData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Determinar qué campo actualizar
            $updateField = '';
            if ($messageData['id_remitente'] == $currentUserId) {
                $updateField = 'eliminado_por_remitente';
            } else if ($messageData['id_destinatario'] == $currentUserId || $messageData['tipo_chat'] == 'grupal') {
                $updateField = 'eliminado_por_destinatario';
            } else if ($currentUserRole == 1111) { // Admin puede eliminar cualquier mensaje
                $updateField = 'eliminado_por_remitente'; // O ambos campos según necesidad
            } else {
                throw new Exception('No tienes permiso para eliminar este mensaje');
            }

            // Actualizar el campo correspondiente
            $stmt = $base_de_datos->prepare("UPDATE mensajes_chat SET $updateField = TRUE WHERE id_mensaje = ?");

            if (!$stmt->execute([$messageId])) {
                throw new Exception('Error al eliminar el mensaje');
            }

            $response = [
                'status' => 'success',
                'message' => 'Mensaje eliminado'
            ];
            break;

        case 'get_messages':
            $currentUserId = $decoded->id;

            if (!isset($data['chat_type']) || !isset($data['target_id'])) {
                throw new Exception('Parámetros incompletos');
            }

            $chatType = $data['chat_type'];
            $targetId = $data['target_id'];

            if (!in_array($chatType, ['privado', 'grupal'])) {
                throw new Exception('Tipo de chat no válido');
            }

            // Consulta mejorada para obtener mensajes
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

        default:
            throw new Exception('Acción no reconocida');
    }
} catch (PDOException $e) {
    $response = [
        'status' => 'error',
        'message' => 'Error de base de datos',
        'error_details' => $e->getMessage()
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
}

// Enviar respuesta
ob_clean();
echo json_encode($response);
exit();
