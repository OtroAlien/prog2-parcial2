<?php
class Checkout
{
    public function insert_checkout_data(array $checkoutData, array $detailsData)
    {
        $conexion = Conexion::getConexion();
        
        try {
            $conexion->beginTransaction();
            
            // Insertar en la tabla ordenes
            $query = "INSERT INTO ordenes (user_id, fecha_orden, total, estado) VALUES (?, ?, ?, 'pendiente')";
            $stmt = $conexion->prepare($query);
            $stmt->execute([
                $checkoutData['id_usuario'],
                $checkoutData['fecha'],
                $checkoutData['importe']
            ]);
            
            $ordenId = $conexion->lastInsertId();
            
            // Insertar productos de la orden
            foreach ($detailsData as $productoId => $cantidad) {
                // Obtener precio actual del producto
                $queryPrecio = "SELECT precio FROM productos WHERE product_id = ?";
                $stmtPrecio = $conexion->prepare($queryPrecio);
                $stmtPrecio->execute([$productoId]);
                $precio = $stmtPrecio->fetchColumn();
                
                $queryDetalle = "INSERT INTO ordenes_productos (orden_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmtDetalle = $conexion->prepare($queryDetalle);
                $stmtDetalle->execute([$ordenId, $productoId, $cantidad, $precio]);
            }
            
            // Limpiar el carrito de la base de datos
            $queryLimpiar = "DELETE cp FROM carrito_productos cp 
                           JOIN carrito c ON cp.carrito_id = c.carrito_id 
                           WHERE c.user_id = ?";
            $stmtLimpiar = $conexion->prepare($queryLimpiar);
            $stmtLimpiar->execute([$checkoutData['id_usuario']]);
            
            $conexion->commit();
            return $ordenId;
            
        } catch (Exception $e) {
            $conexion->rollBack();
            throw $e;
        }
    }
    
    public function obtener_ordenes_usuario($userId)
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM ordenes WHERE user_id = ? ORDER BY fecha_orden DESC";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtener_detalle_orden($ordenId)
    {
        $conexion = Conexion::getConexion();
        $query = "
            SELECT op.*, p.nombre, p.imagen
            FROM ordenes_productos op
            JOIN productos p ON op.producto_id = p.product_id
            WHERE op.orden_id = ?
        ";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$ordenId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
