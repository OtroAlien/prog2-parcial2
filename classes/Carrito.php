<?PHP
class Carrito
{

    public function add_item(int $productoID, int $cantidad)
    {
        $itemData = (new Producto)->productoPorId($productoID);

        if ($itemData) {
            $_SESSION['carrito'][$productoID] = [
                'nombre' => $itemData->getNombre(),
                'descripcion' => $itemData->getDescripcion(),
                'imagen' => $itemData->getImagen(),
                'precio' => $itemData->getPrecio(),
                'cantidad' => $cantidad
            ];
        }
    }


    public function remove_item(int $productoID)
    {
        if (isset($_SESSION['carrito'][$productoID])) {
            unset($_SESSION['carrito'][$productoID]);
        }
    }

    public function clear_items()
    {
        $_SESSION['carrito'] = [];
    }

    public function update_quantities(array $cantidades)
    {
        foreach ($cantidades as $key => $value) {
            if (isset($_SESSION['carrito'][$key])) {
                $_SESSION['carrito'][$key]['cantidad'] = $value;
            }
        }
    }

    public function get_carrito(): array
    {
        if (!empty($_SESSION['carrito'])) {
            return $_SESSION['carrito'];
        } else {
            return [];
        }
    }

    public function precio_total(): float
    {
        $total = 0;
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }
        }
        return $total;
    }
}
