<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'];

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

    // Asignar el ID del producto recién insertado
    $producto->setId($idProducto);

    // Manejar las subcategorías seleccionadas
    if (isset($postData['subcategorias']) && is_array($postData['subcategorias'])) {
        try {
            $producto->actualizarSubcategorias($postData['subcategorias']);
        } catch (Exception $e) {
            // Ignorar el error de clave duplicada y continuar
            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                throw $e;
            }
        }
    }

    header('Location: ../index.php?sec=admin_productos');
    exit;

} catch (Exception $e) {
    // Si el producto se insertó pero hubo un error en las subcategorías,
    // redirigir al panel de administración
    if (isset($idProducto) && $idProducto > 0) {
        header('Location: ../index.php?sec=admin_productos');
        exit;
    }
    
    // Si fue otro tipo de error, mostrar el mensaje
    echo "<pre>";
    print_r($e->getMessage());
    echo "</pre>";
    die("No se pudo cargar el Producto =(");
}
?>
