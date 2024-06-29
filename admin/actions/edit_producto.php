<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    // Obtener el producto por ID
    $producto = (new Producto())->productoPorId($id);

    // Procesar imagen
    if (!empty($fileData['tmp_name'])) {
        $imagen = (new Imagen())->subirImagen(__DIR__ . "/../../img/productos", $fileData);
        (new Imagen())->borrarImagen(__DIR__ . "/../../img/productos/" . $postData['imagen_og']);
    } else {
        $imagen = $postData['imagen_og'];
    }

    // Editar producto
    $producto->edit(
        $postData['nombre'],
        $postData['categoria'],
        $postData['descripcion'],
        $postData['precio'],
        $postData['stock'],
        $postData['lanzamiento'],
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        $imagen
    );

    // arreglar cositas
    (new Alerta())->add_alerta('warning', "Se editaron correctamente los datos del producto.");
    header('Location: ../index.php?sec=admin_productos');

} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor póngase en contacto con el administrador del sistema.");
    header('Location: ../index.php?sec=admin_productos');
}
