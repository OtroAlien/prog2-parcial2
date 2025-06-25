<?php
require_once "../../classes/Conexion.php";

$id = $_GET['id'] ?? false;

try {
    if (!$id) {
        throw new Exception("ID de descuento no proporcionado.");
    }

    $conexion = Conexion::getConexion();

    // Reasignar a descuento_id = 0 todos los productos con este descuento
    $stmtUpdate = $conexion->prepare("UPDATE productos SET descuento_id = 0 WHERE descuento_id = :id");
    $stmtUpdate->execute(['id' => $id]);

    // Eliminar el descuento
    $stmtDelete = $conexion->prepare("DELETE FROM descuentos WHERE descuento_id = :id");
    $stmtDelete->execute(['id' => $id]);

    // Redirigir con éxito
    header('Location: ../index.php?sec=admin_productos');
} catch (Exception $e) {
    // Si querés implementar alertas tipo flash, podrías usar tu clase Alerta aquí
    // (new Alerta())->add_alerta('danger', "Error al eliminar el descuento.");
    header('Location: ../index.php?sec=admin_productos');
}
