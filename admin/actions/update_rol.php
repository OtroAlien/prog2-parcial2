<?php
require_once "../functions/autoload.php";

// Verificar que sea una petición AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso no permitido']);
    exit;
}

// Verificar que se recibieron los datos necesarios
if (!isset($_POST['user_id']) || !isset($_POST['rol'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

$user_id = $_POST['user_id'];
$rol = $_POST['rol'];

// Validar el rol (solo permitir valores específicos)
$roles_permitidos = ['usuario', 'admin', 'superadmin'];
if (!in_array($rol, $roles_permitidos)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Rol no válido']);
    exit;
}

try {
    $conexion = Conexion::getConexion();
    $query = "UPDATE usuarios SET rol = :rol WHERE user_id = :user_id";
    $stmt = $conexion->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id,
        ':rol' => $rol
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el rol o el usuario no existe']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el rol: ' . $e->getMessage()]);
}