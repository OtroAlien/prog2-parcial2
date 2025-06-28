<?php

class Subcategoria {
    private $id;
    private $nombre;

    public function __construct($id = null, $nombre = null) {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    // Obtener todas las subcategorías
    public static function obtenerTodas() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT * FROM subcategorias ORDER BY name ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        $resultados = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = new Subcategoria($fila['subcategoria_id'], $fila['name']);
        }

        return $resultados;
    }

    // Obtener subcategorías de un producto específico
    public static function obtenerPorProductoId($productoId) {
        $conexion = Conexion::getConexion();
        $sql = "
            SELECT s.subcategoria_id, s.name
            FROM subcategorias s
            JOIN productos_subcategorias ps ON s.subcategoria_id = ps.subcategoria_id
            WHERE ps.producto_id = :producto_id
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':producto_id', $productoId, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = new Subcategoria($fila['subcategoria_id'], $fila['name']);
        }

        return $resultados;
    }
    
    // Obtener subcategoría por ID
    public static function subcategoriaPorId($id) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT * FROM subcategorias WHERE subcategoria_id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
            return new Subcategoria($fila['subcategoria_id'], $fila['name']);
        }
        return null;
    }
    
    // Crear nueva subcategoría
    public function crear() {
        $conexion = Conexion::getConexion();
        $sql = "INSERT INTO subcategorias (name) VALUES (:nombre)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // Actualizar subcategoría
    public function actualizar() {
        $conexion = Conexion::getConexion();
        $sql = "UPDATE subcategorias SET name = :nombre WHERE subcategoria_id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Eliminar subcategoría
    public function eliminar() {
        $conexion = Conexion::getConexion();
        
        try {
            // Verificar si hay productos asociados a esta subcategoría
            $contarProductos = self::contarProductosPorSubcategoria($this->id);
            
            if ($contarProductos > 0) {
                throw new Exception("No se puede eliminar la subcategoría '{$this->nombre}' porque tiene {$contarProductos} producto(s) asociado(s). Primero debe reasignar o eliminar los productos.");
            }
            
            $conexion->beginTransaction();
            
            // Eliminar la subcategoría (no hay productos asociados)
            $eliminarSubcategoria = $conexion->prepare("DELETE FROM subcategorias WHERE subcategoria_id = :id");
            $eliminarSubcategoria->bindParam(':id', $this->id, PDO::PARAM_INT);
            $eliminarSubcategoria->execute();
            
            $conexion->commit();
            return true;
        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }
    
    // Contar productos por subcategoría
    public static function contarProductosPorSubcategoria(int $subcategoria_id): int {
        $conexion = Conexion::getConexion();
        $query = "SELECT COUNT(*) FROM productos_subcategorias WHERE subcategoria_id = :subcategoria_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['subcategoria_id' => $subcategoria_id]);
        return (int)$stmt->fetchColumn();
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}
