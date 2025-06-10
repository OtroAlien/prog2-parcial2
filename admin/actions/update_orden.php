<?php
require_once "../../functions/autoload.php";

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['orden_id']) || !isset($_POST['estado'])) {
    (new Alerta())->add_alerta('danger', "Datos incompletos para actualizar la orden");
    header("Location: ../index.php?sec=admin_ordenes");
    exit;
}

$orden_id = $_POST['orden_id'];
$estado = $_POST['estado'];

try {
    // Actualizar el estado de la orden en la base de datos
    $conexion = Conexion::getConexion();
    $query = "UPDATE ordenes SET estado = ? WHERE orden_id = ?";
    
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([$estado, $orden_id]);
    
    if ($PDOStatement->rowCount() > 0) {
        (new Alerta())->add_alerta('success', "Orden #$orden_id actualizada correctamente");
    } else {
        (new Alerta())->add_alerta('warning', "No se realizaron cambios en la orden #$orden_id");
    }
    
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Error al actualizar la orden: " . $e->getMessage());
}

// Redireccionar de vuelta a la lista de órdenes
header("Location: ../index.php?sec=admin_ordenes");
exit;