<?php
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;

if (!$id) {
    header("Location: ../admin/index.php?sec=admin_descuentos&error=Id inválido");
    exit;
}

try {
    $conexion = Conexion::getConexion();
    $conexion->beginTransaction();

    // 1. Quitar el descuento de los productos que lo tienen asignado
    $sqlActualizarProductos = "UPDATE productos SET descuento = NULL WHERE descuento = :id";
    $stmtActualizar = $conexion->prepare($sqlActualizarProductos);
    $stmtActualizar->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtActualizar->execute();

    // 2. Eliminar el descuento
    $sqlEliminarDescuento = "DELETE FROM descuentos WHERE descuento_id = :id";
    $stmtEliminar = $conexion->prepare($sqlEliminarDescuento);
    $stmtEliminar->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtEliminar->execute();

    $conexion->commit();

    header("Location: ../admin/index.php?sec=admin_descuentos&msg=Descuento eliminado correctamente");
    exit;
} catch (Exception $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    header("Location: ../admin/index.php?sec=admin_descuentos&error=Error al eliminar el descuento: " . urlencode($e->getMessage()));
    exit;
}
