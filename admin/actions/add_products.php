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

    // Subir imagen
    $portada = (new Imagen())->subirImagen(__DIR__ . "/../../img/covers", $fileData);

    // Asegurar valores por defecto
    $postData['waterproof'] = isset($postData['waterproof']) ? 1 : 0;
    $postData['vegano'] = isset($postData['vegano']) ? 1 : 0;
    $postData['productoDestacado'] = isset($postData['productoDestacado']) ? 1 : 0;

    // Insertar producto
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

} catch (Exception $e) {
    echo "<pre>";
    print_r($e->getMessage());
    echo "</pre>";
    die("No se pudo cargar el Producto =(");
}
