<?php

require_once "Conexion.php";
require_once "Categoria.php"; // Asegúrate de incluir esta clase

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

    public static function reasignarProductosACategoriaRespaldo(int $categoria_id, int $categoria_respaldo_id = 4): void
    {
        $conexion = Conexion::getConexion();

        $verifica = $conexion->prepare("SELECT COUNT(*) FROM categorias WHERE categoria_id = :id");
        $verifica->execute(['id' => $categoria_respaldo_id]);
        if ($verifica->fetchColumn() == 0) {
            $crear = $conexion->prepare("INSERT INTO categorias (categoria_id, nombre) VALUES (:id, 'Sin categoría')");
            $crear->execute(['id' => $categoria_respaldo_id]);
        }

        $sql = "UPDATE productos SET categoria_id = :respaldo WHERE categoria_id = :actual";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            'respaldo' => $categoria_respaldo_id,
            'actual' => $categoria_id
        ]);
    }

    public static function eliminarCategoriaConReasignacion(int $categoria_id): bool
    {
        $conexion = Conexion::getConexion();

        $sqlCount = "SELECT COUNT(*) FROM productos WHERE categoria_id = :categoria_id";
        $stmtCount = $conexion->prepare($sqlCount);
        $stmtCount->execute(['categoria_id' => $categoria_id]);
        $total = $stmtCount->fetchColumn();

        if ($total > 0) {
            self::reasignarProductosACategoriaRespaldo($categoria_id);
        }

        $sqlDelete = "DELETE FROM categorias WHERE categoria_id = :categoria_id";
        $stmtDelete = $conexion->prepare($sqlDelete);
        return $stmtDelete->execute(['categoria_id' => $categoria_id]);
    }

    public static function categoriaPorId(int $id): ?Categoria
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM categorias WHERE categoria_id = :id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($datos = $stmt->fetch()) {
            $categoria = new Categoria();
            $categoria->setId($datos['categoria_id']);
            $categoria->setNombre($datos['nombre']);
            return $categoria;
        }

        return null;
    }


    public function productos_x_rango(int $minimo = 0, int $maximo = 0): array
    {
        $conexion = Conexion::getConexion();
        if ($maximo) {
            $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.precio BETWEEN :minimo AND :maximo;";
            $valores = [
                'minimo' => $minimo,
                'maximo' => $maximo
            ];
        } else {
            $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.precio > :minimo";
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

    public function insert($nombre, $descripcion, $precio, $imagen, $stock, $categoria_id, $lanzamiento, $contenido, $descuento, $waterproof, $vegano, $productoDestacado): int
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock, categoria_id, lanzamiento, contenido, descuento, waterproof, vegano, productoDestacado) VALUES (:nombre, :descripcion, :precio, :imagen, :stock, :categoria_id, :lanzamiento, :contenido, :descuento, :waterproof, :vegano, :productoDestacado)";
    
        $PDOStatement = $conexion->prepare($query);
        
        if (!$PDOStatement->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen' => $imagen,
            'stock' => $stock,
            'categoria_id' => $categoria_id,
            'lanzamiento' => $lanzamiento,
            'contenido' => $contenido,
            'descuento' => $descuento,
            'waterproof' => $waterproof,
            'vegano' => $vegano,
            'productoDestacado' => $productoDestacado
        ])) {
            print_r($PDOStatement->errorInfo());
        }
        
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
            categoria_id = :categoria_id,
            lanzamiento = :lanzamiento,
            contenido = :contenido,
            descuento = :descuento,
            waterproof = :waterproof,
            vegano = :vegano,
            productoDestacado = :productoDestacado
            WHERE product_id = :product_id";
    
        $PDOStatement = $conexion->prepare($query);
        
        if (!$PDOStatement->execute([
            'product_id' => $this->product_id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen' => $imagen,
            'stock' => $stock,
            'categoria_id' => $categoria_id,
            'lanzamiento' => $lanzamiento,
            'contenido' => $contenido,
            'descuento' => $descuento,
            'waterproof' => $waterproof,
            'vegano' => $vegano,
            'productoDestacado' => $productoDestacado
        ])) {
            print_r($PDOStatement->errorInfo());
        }
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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE c.nombre = :categoria";

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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.descuento = :descuento";

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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.productoDestacado = :productoDestacado";

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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.piel = :piel";

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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.product_id = :product_id";

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

    public function buscarProductos(string $busqueda): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre 
                  FROM productos p 
                  JOIN categorias c ON p.categoria_id = c.categoria_id 
                  WHERE p.nombre LIKE :busqueda OR c.nombre LIKE :busqueda";
    
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute(['busqueda' => '%' . $busqueda . '%']);
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
    
    public function catalogoCompleto(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre 
                  FROM productos p 
                  JOIN categorias c ON p.categoria_id = c.categoria_id";
    
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute();
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
    


    public function precioDescuento(): string
    {
        $resultado = number_format(($this->precio - ($this->precio * $this->descuento /100)), 2, ".", ",");
        return $resultado;
    }

    public function precioFormateado(): string
    {
        return number_format($this->precio, 2, ".", ",");
    }


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
    
    public function getCategoriaId()
    {
        return $this->categoria->getId();
    }
    
    private static function createProducto(array $data): Producto
    {
        $producto = new self();
        
        foreach (self::$createValues as $value) {
            if (isset($data[$value])) {
                $producto->{$value} = $data[$value];
            }
        }
        
        if (isset($data['categoria'])) {
            $categoria = new Categoria();
            $categoria->setId($data['categoria']['categoria_id']);
            $categoria->setNombre($data['categoria']['nombre']);
            $producto->categoria = $categoria;
        } else if (isset($data['categoria_id'])) {
            $producto->categoria = self::categoriaPorId($data['categoria_id']);
        }
        
        if (isset($data['piel'])) {
            $producto->piel = $data['piel'];
        }
        
        return $producto;
    }
    
    public function remove_producto(int $id): void
    {
        $this->product_id = $id;
        $this->delete();
    }

    public static function obtenerTodosDescuentos(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT DISTINCT descuento FROM productos ORDER BY descuento ASC";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $descuentos = [];
        while ($datos = $stmt->fetch()) {
            $descuento = new stdClass();
            $descuento->valor = $datos['descuento'];
            $descuento->nombre = $datos['descuento'] . '%';
            $descuentos[] = $descuento;
        }
        
        return $descuentos;
    }
    
    public static function obtenerTodosContenidos(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT DISTINCT contenido FROM productos ORDER BY contenido ASC";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $contenidos = [];
        while ($datos = $stmt->fetch()) {
            $contenido = new stdClass();
            $contenido->valor = $datos['contenido'];
            $contenido->nombre = $datos['contenido'] . ' ml';
            $contenidos[] = $contenido;
        }
        
        return $contenidos;
    }
}

?>
