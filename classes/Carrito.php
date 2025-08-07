<?php
class Carrito
{
    private $user_id;
    private $carrito_id;

    public function __construct($user_id = null)
{
    $this->user_id = $user_id;
    if ($this->user_id) {
        $this->carrito_id = $this->obtenerOCrearCarrito();
    }
}


    private function obtenerOCrearCarrito(): int
    {
        $conexion = Conexion::getConexion();
        
        // Buscar carrito existente
        $query = "SELECT carrito_id FROM carrito WHERE user_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->user_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            return $resultado['carrito_id'];
        }
        
        // Crear nuevo carrito
        $query = "INSERT INTO carrito (user_id) VALUES (?)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->user_id]);
        
        return $conexion->lastInsertId();
    }

    // NUEVO MÉTODO: Migrar carrito de sesión a base de datos
    public function migrarCarritoSesionABD()
    {
        if (!$this->user_id || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            return;
        }

        foreach ($_SESSION['carrito'] as $productoId => $item) {
            $this->add_item_bd($productoId, $item['cantidad']);
        }
        
        // Limpiar carrito de sesión después de migrar
        unset($_SESSION['carrito']);
    }

    // NUEVO MÉTODO: Cargar carrito desde BD a sesión
    public function cargarCarritoASession()
    {
        if (!$this->user_id) {
            return;
        }

        // Primero migrar cualquier producto que esté en sesión
        $this->migrarCarritoSesionABD();
    
        // Luego cargar desde la base de datos
        $conexion = Conexion::getConexion();
        $query = "SELECT cp.product_id, cp.cantidad, p.nombre, p.descripcion, p.imagen, p.precio 
                FROM carrito_productos cp 
                JOIN productos p ON cp.product_id = p.product_id 
                WHERE cp.carrito_id = ?";
        
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->carrito_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // DEBUG: Aquí SÍ puedes usar $this
        if (empty($productos)) {
            error_log("⚠️ No se encontraron productos en el carrito del usuario ID: {$this->user_id}");
        } else {
            error_log("✅ Se cargaron " . count($productos) . " productos del carrito");
        }
    
        // Cargar productos en la sesión
        $_SESSION['carrito'] = [];
        foreach ($productos as $producto) {
            $_SESSION['carrito'][$producto['product_id']] = [
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'imagen' => $producto['imagen'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
            ];
        }
    }

    public function add_item(int $productoID, int $cantidad = 1)
    {
        // Siempre agregar a la sesión primero
        $this->add_item_session($productoID, $cantidad);
        
        // Si el usuario está logueado, también guardar en BD
        if ($this->user_id) {
            $this->add_item_bd($productoID, $cantidad);
        }
    }

    private function add_item_bd(int $productoID, int $cantidad)
    {
        $conexion = Conexion::getConexion();
        
        // Verificar si el producto ya está en el carrito
        $query = "SELECT cantidad FROM carrito_productos WHERE carrito_id = ? AND product_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->carrito_id, $productoID]);
        $existente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existente) {
            // Actualizar cantidad
            $nuevaCantidad = $existente['cantidad'] + $cantidad;
            $query = "UPDATE carrito_productos SET cantidad = ? WHERE carrito_id = ? AND product_id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$nuevaCantidad, $this->carrito_id, $productoID]);
        } else {
            // Insertar nuevo producto
            $query = "INSERT INTO carrito_productos (carrito_id, product_id, cantidad) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$this->carrito_id, $productoID, $cantidad]);
        }
    }

    private function add_item_session(int $productoID, int $cantidad)
    {
        $itemData = (new Producto)->productoPorId($productoID);

        if ($itemData) {
            if (isset($_SESSION['carrito'][$productoID])) {
                $_SESSION['carrito'][$productoID]['cantidad'] += $cantidad;
            } else {
                $_SESSION['carrito'][$productoID] = [
                    'nombre' => $itemData->getNombre(),
                    'descripcion' => $itemData->getDescripcion(),
                    'imagen' => $itemData->getImagen(),
                    'precio' => $itemData->getPrecio(),
                    'cantidad' => $cantidad
                ];
            }
        }
    }

    public function remove_item(int $productoID)
    {
        // Remover de la sesión
        if (isset($_SESSION['carrito'][$productoID])) {
            unset($_SESSION['carrito'][$productoID]);
        }
        
        // Si el usuario está logueado, también remover de BD
        if ($this->user_id) {
            $conexion = Conexion::getConexion();
            $query = "DELETE FROM carrito_productos WHERE carrito_id = ? AND product_id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$this->carrito_id, $productoID]);
        }
    }

    public function update_quantities(array $cantidades)
    {
        foreach ($cantidades as $productoID => $cantidad) {
            if ($cantidad > 0) {
                // Actualizar en sesión
                if (isset($_SESSION['carrito'][$productoID])) {
                    $_SESSION['carrito'][$productoID]['cantidad'] = $cantidad;
                }
                
                // Actualizar en BD si está logueado
                if ($this->user_id) {
                    $conexion = Conexion::getConexion();
                    $query = "UPDATE carrito_productos SET cantidad = ? WHERE carrito_id = ? AND product_id = ?";
                    $stmt = $conexion->prepare($query);
                    $stmt->execute([$cantidad, $this->carrito_id, $productoID]);
                }
            } else {
                // Si cantidad es 0, remover el producto
                $this->remove_item($productoID);
            }
        }
    }

    public function clear_items()
    {
        // Limpiar sesión
        if (isset($_SESSION['carrito'])) {
            unset($_SESSION['carrito']);
        }
        
        // Limpiar BD si está logueado
        if ($this->user_id) {
            $conexion = Conexion::getConexion();
            $query = "DELETE FROM carrito_productos WHERE carrito_id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$this->carrito_id]);
        }
    }

    public function get_carrito(): array
    {
        // Si el usuario está logueado, obtener desde la base de datos
        if ($this->user_id && $this->carrito_id) {
            $conexion = Conexion::getConexion();
            $query = "SELECT cp.product_id, cp.cantidad, p.nombre, p.descripcion, p.imagen, p.precio 
                    FROM carrito_productos cp 
                    JOIN productos p ON cp.product_id = p.product_id 
                    WHERE cp.carrito_id = ?
                    ORDER BY p.nombre";
            
            $stmt = $conexion->prepare($query);
            $stmt->execute([$this->carrito_id]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatear los datos para mantener compatibilidad con la vista
            $carrito = [];
            foreach ($productos as $producto) {
                $carrito[$producto['product_id']] = [
                    'nombre' => $producto['nombre'],
                    'descripcion' => $producto['descripcion'],
                    'imagen' => $producto['imagen'],
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad']
                ];
            }
            
            return $carrito;
        }
        
        // Si no está logueado, usar la sesión como fallback
        return $_SESSION['carrito'] ?? [];
    }

    public function precio_total(): float
    {
        $total = 0;
        $carrito = $this->get_carrito(); // Usar el método actualizado
        
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return $total;
    }

    public function contar_items(): int
    {
        $total = 0;
        $carrito = $this->get_carrito(); // Usar el método actualizado
        
        foreach ($carrito as $item) {
            $total += $item['cantidad'];
        }
        
        return $total;
    } // ← La clase debe terminar aquí, sin código adicional
}
