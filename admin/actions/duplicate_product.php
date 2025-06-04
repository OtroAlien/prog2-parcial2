<?PHP
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? FALSE;

try {
    if (!$id) {
        throw new Exception("ID de producto no proporcionado.");
    }

    // Obtener el producto original
    $productoOriginal = (new Producto())->productoPorId($id);
    
    if (!$productoOriginal) {
        throw new Exception("Producto no encontrado.");
    }
    
    // Crear un nuevo producto con los mismos datos pero con un nombre modificado
    $nuevoNombre = $productoOriginal->getNombre() . " (Copia)";
    
    // Insertar el nuevo producto
    $producto = new Producto();
    $idNuevoProducto = $producto->insert(
        $nuevoNombre,
        $productoOriginal->getDescripcion(),
        $productoOriginal->getPrecio(),
        $productoOriginal->getImagen(),
        $productoOriginal->getStock(),
        $productoOriginal->getCategoriaId(),
        $productoOriginal->getLanzamiento(),
        $productoOriginal->getContenido(),
        $productoOriginal->getDescuento(),
        $productoOriginal->getWaterproof(),
        $productoOriginal->getVegano(),
        $productoOriginal->getDestacado()
    );

    // Mostrar mensaje de éxito
    (new Alerta())->add_alerta('success', "El producto ha sido duplicado correctamente.");
    
    // Redireccionar a la página de administración de productos
    header('Location: ../index.php?sec=admin_productos');
    exit;
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Ocurrió un error al duplicar el producto: " . $e->getMessage());
    header('Location: ../index.php?sec=admin_productos');
    exit;
}