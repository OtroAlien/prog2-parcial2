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
        $imagen = (new Imagen())->subirImagen(__DIR__ . "/../../img/products", $fileData);
        (new Imagen())->borrarImagen(__DIR__ . "/../../img/products/" . $postData['imagen_actual']);
    } else {
        $imagen = $postData['imagen_actual'];
    }

    // Editar producto
    $producto->edit(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $imagen,
        $postData['stock'],
        $postData['categoria'],
        $postData['lanzamiento'],
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        $postData['piel'],
        $postData['productoDestacado']
    );

    // Mensaje de éxito
    (new Alerta())->add_alerta('warning', "Se editaron correctamente los datos del producto.");
    header('Location: ../index.php?sec=admin_productos');

} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor póngase en contacto con el administrador del sistema.");
    header('Location: ../index.php?sec=admin_productos');
}
