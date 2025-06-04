<?php

require_once "Conexion.php";

class Categoria
{
    private int $categoria_id;
    private string $nombre;

    // Constructor opcional
    public function __construct(int $id = 0, string $nombre = "") {
        $this->categoria_id = $id;
        $this->nombre = $nombre;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->categoria_id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    // Getters
    public function getId(): int
    {
        return $this->categoria_id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    // Crear instancia desde array (útil para Producto)
    public static function createCategoria(array $data): Categoria
    {
        return new self((int)$data['categoria_id'], $data['nombre']);
    }

    // Obtener categoría por ID
    public static function categoriaPorId(int $id): ?Categoria
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM categorias WHERE categoria_id = :id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($datos = $stmt->fetch()) {
            return new self((int)$datos['categoria_id'], $datos['nombre']);
        }

        return null;
    }
    
    // Obtener todas las categorías
    public static function obtenerTodas(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM categorias ORDER BY nombre ASC";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $categorias = [];
        while ($datos = $stmt->fetch()) {
            $categorias[] = new self((int)$datos['categoria_id'], $datos['nombre']);
        }
        
        return $categorias;
    }
    
    // Agregar nueva categoría
    public static function agregarCategoria(string $nombre): int
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO categorias (nombre) VALUES (:nombre)";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['nombre' => $nombre]);
        
        return $conexion->lastInsertId();
    }
    
    // Actualizar categoría existente
    public function actualizar(): bool
    {
        $conexion = Conexion::getConexion();
        $query = "UPDATE categorias SET nombre = :nombre WHERE categoria_id = :id";
        $stmt = $conexion->prepare($query);
        return $stmt->execute([
            'nombre' => $this->nombre,
            'id' => $this->categoria_id
        ]);
    }
    
    // Contar productos por categoría
    public static function contarProductosPorCategoria(int $categoria_id): int
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT COUNT(*) FROM productos WHERE categoria_id = :categoria_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['categoria_id' => $categoria_id]);
        return (int)$stmt->fetchColumn();
    }
}
