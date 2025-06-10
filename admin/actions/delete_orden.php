<?php
require_once "../../functions/autoload.php";

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['orden_id'])) {
    (new Alerta())->add_alerta('danger', "ID de orden no proporcionado");
    header("Location: ../index.php?sec=admin_ordenes");
    exit;
}

$orden_id = $_POST['orden_id'];

try {
    // Eliminar primero los productos relacionados con la orden
    $conexion = Conexion::getConexion();
    $query = "DELETE FROM ordenes_productos WHERE orden_id = ?";
    
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([$orden_id]);
    
    // Luego eliminar la orden
    $query = "DELETE FROM ordenes WHERE orden_id = ?";
    
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([$orden_id]);
    
    if ($PDOStatement->rowCount() > 0) {
        (new Alerta())->add_alerta('success', "Orden #$orden_id eliminada correctamente");
    } else {
        (new Alerta())->add_alerta('warning', "No se pudo eliminar la orden #$orden_id");
    }
    
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Error al eliminar la orden: " . $e->getMessage());
}

// Redireccionar de vuelta a la lista de órdenes
header("Location: ../index.php?sec=admin_ordenes");
exit;