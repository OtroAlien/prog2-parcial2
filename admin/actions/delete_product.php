<?PHP
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? FALSE;

try {
    if (!$id) {
        throw new Exception("ID de producto no proporcionado.");
    }

    // esto obtiene el producto por su id
    $producto = (new Producto())->producto_x_id($id);

    // esto elimina el producto de la db
    $producto->delete();

    // despues vamos a volver a la pagina de administracion de productos
    header('Location: ../index.php?sec=admin_productos');
} catch (Exception $e) {
    // esto hay que arreglarlo para hacer bien la alerta
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor póngase en contacto con el administrador del sistema.");
    header('Location: ../index.php?sec=admin_productos');
}
