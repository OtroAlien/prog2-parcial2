<?PHP

class Compra
{

     /**
     * Devuelve las compras de un usuario en particular
     * @param int $idUsuario El ID del usuario a mostrar
     */
    public function compras_x_id_usuario(int $idUsuario): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT compras.id, compras.fecha, GROUP_CONCAT(CONCAT(item_x_compra.cantidad, 'x ' ,productos.titulo) SEPARATOR ', ') detalle FROM compras JOIN item_x_compra ON compras.id = item_x_compra.compra_id JOIN comics ON item_x_compra.item_id = comics.id WHERE compras.id_usuario = ? GROUP BY (compras.id);";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$idUsuario]);

        $result = $PDOStatement->fetchAll();

        return $result ?? [];
    }
    
    /**
     * Obtiene todas las órdenes de compra de la base de datos
     * @return array Array con todas las órdenes
     */
    public function obtenerTodasLasOrdenes(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT o.orden_id, u.username, u.nombre_completo, o.fecha_orden, o.total, o.estado, 
                 GROUP_CONCAT(CONCAT(op.cantidad, 'x ', p.nombre, ' ($', op.precio, ')') SEPARATOR ', ') as detalle,
                 GROUP_CONCAT(CONCAT(op.cantidad, 'x ', p.nombre) SEPARATOR ', ') as detalle_sin_precio,
                 GROUP_CONCAT(op.precio SEPARATOR ', ') as precios_unitarios
                 FROM ordenes o
                 JOIN usuarios u ON o.user_id = u.user_id
                 JOIN ordenes_productos op ON o.orden_id = op.orden_id
                 JOIN productos p ON op.producto_id = p.product_id
                 GROUP BY o.orden_id
                 ORDER BY o.fecha_orden DESC";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute();
        
        $result = $PDOStatement->fetchAll();
        
        return $result ?? [];
    }
    
    /**
     * Obtiene una orden específica por su ID
     * @param int $ordenId El ID de la orden a obtener
     * @return array|null Los datos de la orden o null si no existe
     */
    public function obtenerOrdenPorId(int $ordenId): ?array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT o.orden_id, u.username, u.nombre_completo, o.fecha_orden, o.total, o.estado
                 FROM ordenes o
                 JOIN usuarios u ON o.user_id = u.user_id
                 WHERE o.orden_id = ?";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$ordenId]);
        
        $orden = $PDOStatement->fetch();
        
        if (!$orden) {
            return null;
        }
        
        // Obtener los productos de la orden
        $query = "SELECT op.cantidad, p.nombre, p.precio, op.precio as precio_unitario
                 FROM ordenes_productos op
                 JOIN productos p ON op.producto_id = p.product_id
                 WHERE op.orden_id = ?";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute([$ordenId]);
        
        $orden['productos'] = $PDOStatement->fetchAll();
        
        return $orden;
    }

}