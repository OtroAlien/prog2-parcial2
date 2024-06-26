<?php

require_once "Conexion.php";

class Producto
{
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagen;
    private $stock;
    private $categoria;
    private $piel;
    private $lanzamiento;
    private $contenido;
    private $descuento;
    private $waterproof;
    private $vegano;

    private static $createValues = [
        'id', 
        'nombre', 
        'descripcion', 
        'precio', 
        'imagen', 
        'stock', 
        'categoria', 
        'piel', 
        'lanzamiento', 
        'contenido', 
        'descuento', 
        'waterproof', 
        'vegano'
    ];

    private static function createProducto($productoData): Producto
    {
        $producto = new self();

        foreach (self::$createValues as $value) {
            if (isset($productoData[$value])) {
                $producto->{$value} = $productoData[$value];
            }
        }

        return $producto;
    }

    public function catalogoCompleto(): array
    {
        $catalogo = [];

        $conexion = Conexion::getConexion();
        $query = "SELECT productos.*, GROUP_CONCAT(pxc.personaje_id) AS personajes_secundarios FROM productos 
        LEFT JOIN personajes_x_producto AS pxc ON productos.id = pxc.producto_id     
        GROUP BY productos.id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute();

        while ($result = $PDOStatement->fetch()) {
            $catalogo[] = self::createProducto($result);
        }

        return $catalogo;
    }

    public function catalogoPorCategoria(string $categoria): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE categoria = :categoria";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['categoria' => $categoria]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        
        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productos[] = self::createProducto($productoData);
        }
        
        return $productos;
    }

    public function catalogoPorDescuento(float $descuento): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE descuento = :descuento";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['descuento' => $descuento]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        
        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productos[] = self::createProducto($productoData);
        }
        
        return $productos;
    }

    public function catalogoPorDestacado(bool $productoDestacado): array
    {
    $conexion = Conexion::getConexion();
    $query = "SELECT * FROM productos WHERE productoDestacado = :productoDestacado";
    
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute(['productoDestacado' => $productoDestacado ? 1 : 0]);
    $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
    
    $productos = [];
    while ($productoData = $PDOStatement->fetch()) {
        $productos[] = $this->createProducto($productoData);
    }
    
    return $productos;
    }

    public function catalogoPorPiel(string $piel): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE piel = :piel";
        
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['piel' => $piel]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        
        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productos[] = self::createProducto($productoData);
        }
        
        return $productos;
    }

    public function productoPorId(int $id): ?Producto
    {
        $catalogo = $this->catalogoCompleto();

        foreach ($catalogo as $p) {
            if ($p->id == $id) {
                return $p;
            }
        }
        return null;
    }

    public function precioDescuento(): string
    {
        $resultado = number_format(($this->precio - ($this->precio * $this->descuento /100)), 2, ".", ",");
        return $resultado;
    }

    public function precioFormateado(): string
    {
        return number_format($this->precio, 2, ".", ",");
    }

    // Métodos GET para propiedades

    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getPiel()
    {
        return $this->piel;
    }

    public function getLanzamiento()
    {
        return $this->lanzamiento;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescuento()
    {
        return $this->descuento;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function getWaterproof()
    {
        return $this->waterproof;
    }

    public function getVegano()
    {
        return $this->vegano;
    }

    public function getDestacado()
    {
        return $this->productoDestacado;
    }
}
?>
