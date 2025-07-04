<?php

require_once "Conexion.php";
require_once "Categoria.php";

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
    private int $descuento_id;
    private int $descuento_valor;
    private bool $waterproof;
    private bool $vegano;
    private bool $productoDestacado;

    private static $createValues = [
        'product_id', 'nombre', 'descripcion', 'precio', 'imagen', 'stock',
        'lanzamiento', 'contenido', 'descuento_id', 'waterproof', 'vegano', 'productoDestacado'
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

    public function actualizarSubcategorias(array $subcategoria_ids): void
    {
        $conexion = Conexion::getConexion();

        // Eliminar subcategorías anteriores
        $stmt = $conexion->prepare("DELETE FROM productos_subcategorias WHERE producto_id = :producto_id");
        $stmt->execute(['producto_id' => $this->getId()]);

        // Insertar nuevas subcategorías
        $stmt = $conexion->prepare("INSERT INTO productos_subcategorias (producto_id, subcategoria_id) VALUES (:producto_id, :subcategoria_id)");
        foreach ($subcategoria_ids as $subcategoria_id) {
            $stmt->execute([
                'producto_id' => $this->getId(),
                'subcategoria_id' => $subcategoria_id
            ]);
        }
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

    public function insert($nombre, $descripcion, $precio, $imagen, $stock, $categoria_id, $lanzamiento, $contenido, $descuento_id, $waterproof, $vegano, $productoDestacado): int
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock, categoria_id, lanzamiento, contenido, descuento_id, waterproof, vegano, productoDestacado) VALUES (:nombre, :descripcion, :precio, :imagen, :stock, :categoria_id, :lanzamiento, :contenido, :descuento_id, :waterproof, :vegano, :productoDestacado)";
    
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
            'descuento_id' => $descuento_id,
            'waterproof' => $waterproof,
            'vegano' => $vegano,
            'productoDestacado' => $productoDestacado
        ])) {
            print_r($PDOStatement->errorInfo());
        }
        
        return $conexion->lastInsertId();
    }
    
    public function edit(
        $nombre, $descripcion, $precio, $imagen, $stock, $categoria_id,
        $lanzamiento, $contenido, $descuento_id, $waterproof, $vegano, $productoDestacado
    ) {
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
            descuento_id = :descuento_id,
            waterproof = :waterproof,
            vegano = :vegano,
            productoDestacado = :productoDestacado
            WHERE product_id = :product_id";
    
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
            'descuento_id' => $descuento_id,
            'waterproof' => $waterproof,
            'vegano' => $vegano,
            'productoDestacado' => $productoDestacado,
            'product_id' => $this->product_id
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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.categoria_id WHERE p.descuento_id = :descuento";


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
            $producto = self::createProducto($productoData);

$subcategorias = self::obtenerSubcategoriasPorProducto($producto->getId());
$producto->setSubcategorias($subcategorias);
return $producto;
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
        $query = "SELECT p.*, c.categoria_id, c.nombre as categoria_nombre, d.valor as descuento_valor
          FROM productos p
          JOIN categorias c ON p.categoria_id = c.categoria_id
          LEFT JOIN descuentos d ON p.descuento_id = d.descuento_id";

    
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute();
        $PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
    
        $productos = [];
        while ($productoData = $PDOStatement->fetch()) {
            $productoData['categoria'] = [
                'categoria_id' => $productoData['categoria_id'],
                'nombre' => $productoData['categoria_nombre']
            ];
    
            $producto = self::createProducto($productoData);
    
            // Subcategorías
            $subcategorias = self::obtenerSubcategoriasPorProducto($producto->getId());
            $producto->setSubcategorias($subcategorias);
    
            $productos[] = $producto;
        }
    
        return $productos;
    }
    



    public function precioDescuento(): string
    {
        $valor = $this->descuento_valor ?? 0;
        $resultado = $this->precio - ($this->precio * $valor / 100);
        return number_format($resultado, 2, ".", ",");
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
    return $this->piel ?? 'todoTipo';
}

    public function getLanzamiento()
    {
        return $this->lanzamiento;
    }

    public function getId()
    {
        return $this->product_id;
    }

    public function setId(int $id): void
    {
        $this->product_id = $id;
    }

    public function getDescuento(): string
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT valor FROM descuentos WHERE descuento_id = :id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['id' => $this->descuento_id]);
        $valor = $stmt->fetchColumn();
        
        return $valor !== false ? $valor : '0';
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
                switch ($value) {
                    case 'product_id':
                    case 'stock':
                    case 'contenido':
                    case 'descuento_id':
                        $producto->{$value} = (int)$data[$value];
                        break;
                    case 'precio':
                        $producto->{$value} = (float)$data[$value];
                        break;
                    case 'waterproof':
                    case 'vegano':
                    case 'productoDestacado':
                        $producto->{$value} = (bool)$data[$value];
                        break;
                    default:
                        $producto->{$value} = $data[$value];
                        break;
                }
            }
        }

        if (isset($data['descuento_valor'])) {
            $producto->descuento_valor = (int)$data['descuento_valor'];
        }

        if (isset($data['categoria'])) {
            $categoria = new Categoria();
            $categoria->setId($data['categoria']['categoria_id']);
            $categoria->setNombre($data['categoria']['nombre']);
            $producto->categoria = $categoria;
        } elseif (isset($data['categoria_id'])) {
            $producto->categoria = self::categoriaPorId($data['categoria_id']);
        }

        if (isset($data['piel'])) {
            $producto->piel = $data['piel'];
        }

        return $producto;
    }

    private array $subcategorias = [];

    public function setSubcategorias(array $subcategorias): void {
        $this->subcategorias = $subcategorias;
    }

    public function getSubcategorias(): array {
        return $this->subcategorias;
    }
    
    public function remove_producto(int $id): void
    {
        $this->product_id = $id;
        $this->delete();
    }

    public static function obtenerTodosDescuentos(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT descuento_id, valor FROM descuentos ORDER BY valor ASC";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        $descuentos = [];
        while ($datos = $stmt->fetch()) {
            $descuento = new stdClass();
            $descuento->id = $datos['descuento_id'];
            $descuento->valor = $datos['valor'];
            $descuento->nombre = $datos['valor'] . '%';  // esto genera "15%", "30%", etc.
            $descuentos[] = $descuento;
        }
    
        return $descuentos;
    }
    
    
    public static function obtenerSubcategoriasPorProducto(int $producto_id): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT s.subcategoria_id, s.name 
                  FROM productos_subcategorias ps
                  JOIN subcategorias s ON ps.subcategoria_id = s.subcategoria_id
                  WHERE ps.producto_id = :producto_id";
    
        $stmt = $conexion->prepare($query);
        $stmt->execute(['producto_id' => $producto_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        $subcategorias = [];
        while ($row = $stmt->fetch()) {
            $subcategorias[] = new Subcategoria($row['subcategoria_id'], $row['name']);
        }
    
        return $subcategorias;
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
    
    // Contar productos por descuento
    public static function contarProductosPorDescuento(int $descuento_id): int
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT COUNT(*) FROM productos WHERE descuento_id = :descuento_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['descuento_id' => $descuento_id]);
        return (int)$stmt->fetchColumn();
    }
    
    // Obtener descuento por ID
    public static function descuentoPorId($id) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT * FROM descuentos WHERE descuento_id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
            // Retornar un objeto "Descuento" o al menos un objeto con propiedades:
            return (object)[
                'id' => $fila['descuento_id'],
                'nombre' => $fila['nombre'],  // Cambia 'nombre' si tu campo se llama distinto
                'valor' => $fila['valor'],    // si quieres otras propiedades
            ];
        }
        return null;
    }
    
    

    public static function contarProductosPorSubcategoria(int $subcategoria_id): int {
        $conexion = Conexion::getConexion();
        $query = "SELECT COUNT(*) as total FROM producto_subcategorias WHERE subcategoria_id = :id";
        $stmt = $conexion->prepare($query);
        $stmt->execute([':id' => $subcategoria_id]);
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? (int)$resultado['total'] : 0;
    }
    
    public static function obtenerTodasSubcategorias() {
        $conexion = Conexion::getConexion();
        $sql = "SELECT * FROM subcategorias ORDER BY name ASC"; // O el campo que uses para el nombre
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
    
        $resultados = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = new Subcategoria($fila['subcategoria_id'], $fila['name']);
        }
        return $resultados;
    }
    
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
    
    
    // Eliminar descuento con reasignación
    public static function eliminarDescuentoConReasignacion(int $descuento_id): void
    {
        $conexion = Conexion::getConexion();
        
        try {
            $conexion->beginTransaction();
            
            // Reasignar productos a descuento 0 (sin descuento)
            $reasignar = $conexion->prepare("UPDATE productos SET descuento_id = 1 WHERE descuento_id = :descuento_id");
            $reasignar->execute(['descuento_id' => $descuento_id]);
            
            // Eliminar el descuento
            $eliminar = $conexion->prepare("DELETE FROM descuentos WHERE descuento_id = :descuento_id");
            $eliminar->execute(['descuento_id' => $descuento_id]);
            
            $conexion->commit();
        } catch (Exception $e) {
            $conexion->rollBack();
            throw $e;
        }
    }
}
?>