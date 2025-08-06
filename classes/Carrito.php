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
            // Migrar carrito de sesión a base de datos si existe
            $this->migrarCarritoSesion();
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

    private function migrarCarritoSesion()
    {
        // Si hay productos en la sesión, migrarlos a la base de datos
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $productoId => $item) {
                $this->add_item($productoId, $item['cantidad']);
            }
            // Limpiar carrito de sesión después de migrar
            unset($_SESSION['carrito']);
        }
    }

    public function add_item(int $productoID, int $cantidad = 1)
    {
        if (!$this->user_id) {
            // Fallback a sesión si no hay usuario logueado
            return $this->add_item_session($productoID, $cantidad);
        }

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
            return $stmt->execute([$nuevaCantidad, $this->carrito_id, $productoID]);
        } else {
            // Insertar nuevo producto
            $query = "INSERT INTO carrito_productos (carrito_id, product_id, cantidad) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($query);
            return $stmt->execute([$this->carrito_id, $productoID, $cantidad]);
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
        if (!$this->user_id) {
            // Fallback a sesión
            if (isset($_SESSION['carrito'][$productoID])) {
                unset($_SESSION['carrito'][$productoID]);
            }
            return;
        }

        $conexion = Conexion::getConexion();
        $query = "DELETE FROM carrito_productos WHERE carrito_id = ? AND product_id = ?";
        $stmt = $conexion->prepare($query);
        return $stmt->execute([$this->carrito_id, $productoID]);
    }

    public function clear_items()
    {
        if (!$this->user_id) {
            $_SESSION['carrito'] = [];
            return;
        }

        $conexion = Conexion::getConexion();
        $query = "DELETE FROM carrito_productos WHERE carrito_id = ?";
        $stmt = $conexion->prepare($query);
        return $stmt->execute([$this->carrito_id]);
    }

    public function update_quantities(array $cantidades)
    {
        if (!$this->user_id) {
            // Fallback a sesión
            foreach ($cantidades as $key => $value) {
                if (isset($_SESSION['carrito'][$key])) {
                    if ($value > 0) {
                        $_SESSION['carrito'][$key]['cantidad'] = $value;
                    } else {
                        unset($_SESSION['carrito'][$key]);
                    }
                }
            }
            return;
        }

        $conexion = Conexion::getConexion();
        
        foreach ($cantidades as $productoID => $cantidad) {
            if ($cantidad > 0) {
                $query = "UPDATE carrito_productos SET cantidad = ? WHERE carrito_id = ? AND product_id = ?";
                $stmt = $conexion->prepare($query);
                $stmt->execute([$cantidad, $this->carrito_id, $productoID]);
            } else {
                $this->remove_item($productoID);
            }
        }
    }

    public function get_carrito(): array
    {
        if (!$this->user_id) {
            // Fallback a sesión
            return $_SESSION['carrito'] ?? [];
        }

        $conexion = Conexion::getConexion();
        $query = "
            SELECT cp.product_id, cp.cantidad, p.nombre, p.descripcion, p.imagen, p.precio
            FROM carrito_productos cp
            JOIN productos p ON cp.product_id = p.product_id
            WHERE cp.carrito_id = ?
        ";
        
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->carrito_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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

    public function precio_total(): float
    {
        if (!$this->user_id) {
            // Fallback a sesión
            $total = 0;
            if (!empty($_SESSION['carrito'])) {
                foreach ($_SESSION['carrito'] as $item) {
                    $total += $item['precio'] * $item['cantidad'];
                }
            }
            return $total;
        }

        $conexion = Conexion::getConexion();
        $query = "
            SELECT SUM(cp.cantidad * p.precio) as total
            FROM carrito_productos cp
            JOIN productos p ON cp.product_id = p.product_id
            WHERE cp.carrito_id = ?
        ";
        
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->carrito_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] ?? 0;
    }

    public function contar_items(): int
    {
        if (!$this->user_id) {
            return count($_SESSION['carrito'] ?? []);
        }

        $conexion = Conexion::getConexion();
        $query = "SELECT COUNT(*) as total FROM carrito_productos WHERE carrito_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$this->carrito_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] ?? 0;
    }
}
