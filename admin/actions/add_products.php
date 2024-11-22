<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'];

echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<pre>";
print_r($_FILES);
echo "</pre>";

try {
    $producto = new Producto();

    if (isset($fileData) && $fileData['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $fileData['name'];
        $tmpArchivo = $fileData['tmp_name'];

        $nombreProducto = isset($postData['nombre']) ? $postData['nombre'] : 'producto';
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreProducto)) . '.jpeg';

        $directorioDestino = __DIR__ . "/../../img/productos/";

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $rutaDestino = $directorioDestino . $nombreUnico;

        if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
            $portada = 'productos/' . $nombreUnico;
        } else {
            throw new Exception("Hubo un error al mover la imagen.");
        }
    } else {
        throw new Exception("No se recibió ningún archivo de imagen o hubo un error.");
    }

    $postData['waterproof'] = isset($postData['waterproof']) ? 1 : 0;
    $postData['vegano'] = isset($postData['vegano']) ? 1 : 0;
    $postData['productoDestacado'] = isset($postData['productoDestacado']) ? 1 : 0;

    $idProducto = $producto->insert(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $portada,
        $postData['stock'],
        $postData['categoria'],
        $postData['lanzamiento'],
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        $postData['productoDestacado']
    );

    header('Location: ../index.php?sec=admin_productos');
    exit;

} catch (Exception $e) {
    echo "<pre>";
    print_r($e->getMessage());
    echo "</pre>";
    die("No se pudo cargar el Producto =(");
}
?>
