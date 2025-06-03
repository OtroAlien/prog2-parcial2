<?php
require_once "../../functions/autoload.php";

// Verificar que se haya enviado el formulario y que exista un valor antiguo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['valor'])) {
    $valor_antiguo = $_GET['valor'];
    $valor_nuevo = $_POST['valor'] ?? '';
    
    try {
        // Validar que el valor no esté vacío
        if ($valor_nuevo === '') {
            throw new Exception("El valor del contenido no puede estar vacío.");
        }
        
        // Validar que el valor sea numérico
        if (!is_numeric($valor_nuevo)) {
            throw new Exception("El valor del contenido debe ser un número.");
        }
        
        // Actualizar todos los productos que tengan el contenido antiguo
        $conexion = Conexion::getConexion();
        $query = "UPDATE productos SET contenido = :valor_nuevo WHERE contenido = :valor_antiguo";
        $stmt = $conexion->prepare($query);
        $resultado = $stmt->execute([
            'valor_nuevo' => $valor_nuevo,
            'valor_antiguo' => $valor_antiguo
        ]);
        
        if ($resultado) {
            (new Alerta())->add_alerta('success', "Contenido actualizado correctamente.");
        } else {
            throw new Exception("Error al actualizar el contenido.");
        }
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', $e->getMessage());
    }
}

// Redireccionar de vuelta a la página de administración de productos
header('Location: ../index.php?sec=admin_productos');
exit;