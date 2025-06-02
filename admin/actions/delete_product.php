    <?PHP
    require_once "../../functions/autoload.php";

    $id = $_GET['id'] ?? FALSE;

    try {
        if (!$id) {
            throw new Exception("ID de producto no proporcionado.");
        }

        $producto = (new Producto())->productoPorId($id);

        $producto->delete();


        header('Location: ../index.php?sec=admin_productos');
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor póngase en contacto con el administrador del sistema.");
        header('Location: ../index.php?sec=admin_productos');
    }
