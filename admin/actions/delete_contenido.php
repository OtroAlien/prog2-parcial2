<?php
require_once "../../functions/autoload.php";

$valor = $_GET['valor'] ?? false;

try {
    if (!$valor && $valor !== '0') {
        throw new Exception("Valor de contenido no proporcionado.");
    }
    
    // Crear método para eliminar contenido
    $conexion = Conexion::getConexion();
    
    // Primero verificamos si hay productos con este contenido
    $query = "SELECT COUNT(*) FROM productos WHERE contenido = :contenido";
    $stmt = $conexion->prepare($query);
    $stmt->execute(['contenido' => $valor]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        // Si hay productos con este contenido, los actualizamos a un valor predeterminado (30ml)
        $updateQuery = "UPDATE productos SET contenido = 30 WHERE contenido = :contenido";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->execute(['contenido' => $valor]);
        
        (new Alerta())->add_alerta('warning', "Se han actualizado $count productos que tenían este contenido a 30ml.");
    }
    
    (new Alerta())->add_alerta('success', "Contenido eliminado correctamente.");
    
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', $e->getMessage());
}

header('Location: ../index.php?sec=admin_productos');
exit;