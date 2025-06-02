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
}
