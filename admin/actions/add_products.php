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

    $portada = (new Imagen())->subirImagen(__DIR__ . "/../../img/covers", $fileData);

    $idProducto = $producto->insert(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $postData['stock'],
        $postData['categoria'],
        $postData['lanzamiento'], 
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        $portada,
    );


    header('Location: ../index.php?sec=admin_productos');

} catch (Exception $e) {
    echo "<pre>";
    print_r($e->getMessage());
    echo "<pre>";
    die("No se pudo cargar el Producto =(");
}