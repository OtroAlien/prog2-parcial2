<?php

require_once "Conexion.php";

class Producto
{
    private int $product_id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private string $imagen;
    private int $stock;
    private Categoria $categoria;
    private string $piel;
    private string $lanzamiento;
    private int $contenido;
    private int $descuento;
    private bool $waterproof;
    private bool $vegano;
    private bool $productoDestacado;
    
    private static $createValues = [
        'product_id', 
        'nombre', 
        'descripcion', 
        'precio', 
        'imagen', 
        'stock',
        'lanzamiento', 
        'contenido', 
        'descuento',
        'waterproof', 
        'vegano',
        'productoDestacado'
    ];



    private static function createProducto($productoData): Producto
    {
        $producto = new self();

        foreach (self::$createValues as $value) {
            if (isset($productoData[$value])) {
                $producto->{$value} = $productoData[$value];
            }
        }

        // Asumiendo que $productoData['categoria'] contiene un array con los datos de la categoría
        if (isset($productoData['categoria']) && is_array($productoData['categoria'])) {
            $producto->categoria = Categoria::createCategoria($productoData['categoria']);
        }

        return $producto;
    }

    public function catalogoCompleto(): array
    {
        $catalogo = [];
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id";
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute();

        while ($result = $PDOStatement->fetch()) {
            $result['categoria'] = [
                'categoria_id' => $result['categoria_id'],
                'nombre' => $result['categoria_nombre']
            ];
            $catalogo[] = $this->createProducto($result);
        }

        return $catalogo;
    }

    public function productos_x_rango(int $minimo = 0, int $maximo = 0): array
    {
        $conexion = Conexion::getConexion();
        if ($maximo) {
            $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE precio BETWEEN :minimo AND :maximo;";
            $valores = [
                'minimo' => $minimo,
                'maximo' => $maximo
            ];
        } else {
            $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE precio > :minimo";
            $valores = [
                'minimo' => $minimo
            ];
        }

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $PDOStatement->execute($valores);

        $catalogo = [];
        while ($result = $PDOStatement->fetch()) {
            $result['categoria'] = [
                'categoria_id' => $result['categoria_id'],
                'nombre' => $result['categoria_nombre']
            ];
            $catalogo[] = $this->createProducto($result);
        }

        return $catalogo;
    }

    public function insert($nombre, $descripcion, $imagen, $precio, $stock, $categoria_id, $lanzamiento, $contenido, $descuento, $waterproof, $vegano, $productoDestacado): int
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO productos VALUES (NULL, :nombre, :descripcion, :precio, :imagen, :stock, :categoria, :lanzamiento, :contenido, :descuento, :waterproof, :vegano, :productoDestacado)";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen' => $imagen,
                'stock' => $stock,
                'categoria' => $categoria_id,
                'lanzamiento' => $lanzamiento,
                'contenido' => $contenido,
                'descuento' => $descuento,
                'waterproof' => $waterproof,
                'vegano' => $vegano,
                'productoDestacado' => $productoDestacado
            ]
        );

        return $conexion->lastInsertId();
    }

    public function edit($nombre, $descripcion, $precio, $imagen, $stock, $categoria_id, $lanzamiento, $contenido, $descuento, $waterproof, $vegano, $productoDestacado)
    {
        $conexion = Conexion::getConexion();
        $query = "UPDATE productos SET
            nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            imagen = :imagen,
            stock = :stock,
            categoria = :categoria,
            lanzamiento = :lanzamiento,
            contenido = :contenido,
            descuento = :descuento,
            waterproof = :waterproof,
            vegano = :vegano,
            productoDestacado = :productoDestacado
            WHERE product_id = :product_id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'product_id' => $this->product_id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen' => $imagen,
                'stock' => $stock,
                'categoria' => $categoria_id,
                'lanzamiento' => $lanzamiento,
                'contenido' => $contenido,
                'descuento' => $descuento,
                'waterproof' => $waterproof,
                'vegano' => $vegano,
                'productoDestacado' => $productoDestacado
            ]
        );
    }

    public function delete()
    {
        $conexion = Conexion::getConexion();
        $query = "DELETE FROM productos WHERE product_id = ?";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$this->product_id]);
    }

    public function catalogoPorCategoria(string $categoria): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE c.nombre = :categoria";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['categoria' => $categoria]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
            $productos[] = self::createProducto($productoData);
        }

        return $productos;
    }

    public function catalogoPorDescuento(float $descuento): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE p.descuento = :descuento";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['descuento' => $descuento]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
            $productos[] = self::createProducto($productoData);
        }

        return $productos;
    }

    public function catalogoDestacado(bool $productoDestacado): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE p.productoDestacado = :productoDestacado";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['productoDestacado' => $productoDestacado ? 1 : 0]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
            $productos[] = self::createProducto($productoData);
        }

        return $productos;
    }

    public function catalogoPorPiel(string $piel): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE p.piel = :piel";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['piel' => $piel]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
            $productos[] = self::createProducto($productoData);
        }

        return $productos;
    }

    public function productoPorId(int $product_id): ?Producto
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria = c.categoria_id WHERE p.product_id = :product_id";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['product_id' => $product_id]);
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);

        if ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
            return self::createProducto($productoData);
        } else {
            return null;
        }
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
        return $this->categoria->getNombre();
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
        return $this->product_id;
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
