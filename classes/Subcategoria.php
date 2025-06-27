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
}
