<?php
require_once "../../functions/autoload.php";

$valor = $_GET['valor'] ?? false;

try {
    if (!$valor && $valor !== '0') {
        throw new Exception("Valor de descuento no proporcionado.");
    }
    
    // Crear método para eliminar descuento
    $conexion = Conexion::getConexion();
    
    // Primero verificamos si hay productos con este descuento
    $query = "SELECT COUNT(*) FROM productos WHERE descuento = :descuento";
    $stmt = $conexion->prepare($query);
    $stmt->execute(['descuento' => $valor]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        // Si hay productos con este descuento, los actualizamos a 0%
        $updateQuery = "UPDATE productos SET descuento = 0 WHERE descuento = :descuento";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->execute(['descuento' => $valor]);
        
        (new Alerta())->add_alerta('warning', "Se han actualizado $count productos que tenían este descuento a 0%.");
    }
    
    (new Alerta())->add_alerta('success', "Descuento eliminado correctamente.");
    
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', $e->getMessage());
}

header('Location: ../index.php?sec=admin_productos');
exit;