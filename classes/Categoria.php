<?php

require_once "Conexion.php";

class Categoria
{
    private int $categoria_id;
    private string $nombre;

    public static function createCategoria(array $categoriaData): Categoria
    {
        $categoria = new self();
        $categoria->categoria_id = $categoriaData['categoria_id'];
        $categoria->nombre = $categoriaData['nombre'];
        return $categoria;
    }

    public function getId(): int
    {
        return $this->categoria_id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }
}