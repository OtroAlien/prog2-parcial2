<?PHP

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

    private function createProducto($productoData): Producto
    {

        $producto = new self();

        foreach (self::$createValues as $value) {
            $producto->{$value} = $productoData[$value];
        }

        // revisar: acá se crean objetos pero no sé si son necesarios en este caso por eso no los puse, revisar ejemplo del profe

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
            $catalogo[] = $this->createProducto($result);
        }

        return $catalogo;
    }

    // esto hay que revisarlo, no lo termino de entender
    // public function buscador(string $terminoBusqueda): array
    // {

    //     $conexion = Conexion::getConexion();
    //     $query = "SELECT * FROM productos WHERE nombre LIKE :termino OR bajada LIKE :termino";

    //     $PDOStatement = $conexion->prepare($query);
    //     $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
    //     $PDOStatement->execute(['termino' => "%$terminoBusqueda%"]);

    //     $catalogo = $PDOStatement->fetchAll();

    //     return $catalogo;
    // }


    public function productos_x_rango(int $minimo = 0, int $maximo = 0)
    {

        $conexion = Conexion::getConexion();
        if ($maximo) {
            $query = "SELECT * FROM productos WHERE precio BETWEEN :minimo AND :maximo;";
            $valores = [
                'minimo' => $minimo,
                'maximo' => $maximo
            ];
        } else {
            $query = "SELECT * FROM productos WHERE precio > :minimo";
            $valores = [
                'minimo' => $minimo
            ];
        }

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute($valores);

        $catalogo = $PDOStatement->fetchAll();

        return $catalogo;
    }

    public function insert($nombre, $descripcion, $imagen, $precio, $stock, $categoria, $lanzamiento, $contenido, $descuento, $waterproof, $vegano, $productoDestacado, $subcategoria): int
    {

        $conexion = Conexion::getConexion();
        $query = "INSERT INTO productos VALUES (NULL, :nombre, :descripcion, :precio, :imagen, :stock, :categoria, :lanzamiento, :contenido, :descuento, :waterproof, :vegano, :productoDestacado, :subcategoria";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(
            [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen' => $imagen,
                'stock' => $stock,
                'categoria' => $categoria,
                'lanzamiento' => $lanzamiento,
                'contenido' => $contenido,
                'descuento' => $descuento,
                'waterproof' => $waterproof,
                'vegano' => $vegano,
                'productoDestacado' => $productoDestacado,
                'subcategoria' => $subcategoria,
            ]
        );

        return $conexion->lastInsertId();
    }

    public function edit($nombre, $descripcion, $precio, $imagen, $stock, $categoria, $lanzamiento, $contenido, $descuento, $waterproof, $vegano, $productoDestacado)
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
                'product_id' => $this->id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen' => $imagen,
                'stock' => $stock,
                'categoria' => $categoria,
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
        $query = "DELETE FROM comics WHERE id = ?";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$this->id]);
    }

        $JSON = file_get_contents('datos/productos.json');
        $JSONData = json_decode($JSON);
    
        foreach ($JSONData as $categoria => $productos) {
            foreach ($productos as $value) {
                $producto = new self();
    
                $producto->id = $value->id;
                $producto->nombre = $value->nombre;
                $producto->descripcion = $value->descripcion;
                $producto->precio = $value->precio;
                $producto->precioAnterior = $value->precioAnterior;
                $producto->imagen = $value->imagen;
                $producto->stock = $value->stock;
                $producto->categoria = $value->categoria;
                $producto->piel = isset($value->piel) ? $value->piel : ["No especificado"];
                $producto->lanzamiento = $value->lanzamiento;
                $producto->contenido = $value->contenido;
                $producto->descuento = $value->descuento;
                $producto->waterproof = $value->waterproof;
                $producto->vegano = $value->vegano;
    
                $catalogo[] = $producto;
            }
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
            $productos[] = $this->createProducto($productoData);
        }
        
        return $productos;
    }
    
    private function createProducto(array $productoData): Producto
    {
        $producto = new self();
        foreach ($productoData as $key => $value) {
            if (property_exists($producto, $key)) {
                $producto->{$key} = $value;
            }
        }
        return $producto;
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
            $productos[] = $this->createProducto($productoData);
        }
        
        return $productos;
    }
    
    private function createProducto(array $productoData): Producto
    {
        $producto = new self();
        foreach ($productoData as $key => $value) {
            if (property_exists($producto, $key)) {
                $producto->{$key} = $value;
            }
        }
        return $producto;
    }

    public function catalogoDestacado(bool $productoDestacado): array
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
    
    private function createProducto(array $productoData): Producto
    {
        $producto = new self();
        foreach ($productoData as $key => $value) {
            if (property_exists($producto, $key)) {
                $producto->{$key} = $value;
            }
        }
        return $producto;
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
            $productos[] = $this->createProducto($productoData);
        }
        
        return $productos;
    }
    
    private function createProducto(array $productoData): Producto
    {
        $producto = new self();
        foreach ($productoData as $key => $value) {
            if (property_exists($producto, $key)) {
                $producto->{$key} = $value;
            }
        }
        return $producto;
    }
    


    

    /**
     * Devuelve los datos de un producto en particular
     * @param int $idProducto El ID único del producto a mostrar 
     */
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

    


    /**
     * Devuelve el precio de la unidad, formateado correctamente
     */
    public function precioFormateado(): string
    {
        return number_format($this->precio, 2, ".", ",");
    }

    // Gets

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

    public function getwaterproof()
    {
        return $this->waterproof;
    }

    public function gatVegano()
    {
        return $this->vegano;
    }

    public function getDestacado()
    {
        return $this->vegano;
    }
}