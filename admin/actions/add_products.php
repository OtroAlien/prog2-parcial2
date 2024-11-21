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

    // Verificar si el archivo fue subido correctamente
    if (isset($fileData) && $fileData['error'] === UPLOAD_ERR_OK) {
        // Obtener el nombre del archivo y el archivo temporal
        $nombreArchivo = $fileData['name'];
        $tmpArchivo = $fileData['tmp_name'];

        // Crear un nombre único basado en el nombre del producto (o cualquier otra lógica que desees)
        $nombreProducto = isset($postData['nombre']) ? $postData['nombre'] : 'producto'; // Usar el nombre del producto
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreProducto)) . '.jpeg'; // Evitar espacios y poner .jpeg

        // Definir el directorio destino
        $directorioDestino = __DIR__ . "/../../img/productos/";

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Ruta completa de destino para la imagen
        $rutaDestino = $directorioDestino . $nombreUnico;

        // Mover la imagen al directorio destino
        if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
            // La imagen se movió correctamente, ahora guardar la ruta en la base de datos
            $portada = 'productos/' . $nombreUnico; // Aquí guardamos la ruta relativa a la carpeta 'img'
        } else {
            // Si hubo un error al mover el archivo
            throw new Exception("Hubo un error al mover la imagen.");
        }
    } else {
        // Si no se subió ningún archivo
        throw new Exception("No se recibió ningún archivo de imagen o hubo un error.");
    }

    // Asegurar valores por defecto
    $postData['waterproof'] = isset($postData['waterproof']) ? 1 : 0;
    $postData['vegano'] = isset($postData['vegano']) ? 1 : 0;
    $postData['productoDestacado'] = isset($postData['productoDestacado']) ? 1 : 0;

    // Insertar producto en la base de datos
    $idProducto = $producto->insert(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $portada, // Aquí guardamos la ruta relativa a la imagen
        $postData['stock'],
        $postData['categoria'],
        $postData['lanzamiento'],
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        $postData['productoDestacado']
    );

    // Redirigir a la página de productos
    header('Location: ../index.php?sec=admin_productos');
    exit;

} catch (Exception $e) {
    echo "<pre>";
    print_r($e->getMessage());
    echo "</pre>";
    die("No se pudo cargar el Producto =(");
}
?>
