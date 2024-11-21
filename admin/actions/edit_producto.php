<?php
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    $producto = (new Producto())->productoPorId($id);

    // Procesar la nueva imagen si se seleccionó una
    if (!empty($fileData['tmp_name'])) {
        $nuevaImagen = (new Imagen())->subirImagen(__DIR__ . "/../../img/productos", $fileData);

        // Borrar la imagen anterior del servidor
        $imagenAnterior = __DIR__ . "/../../img/productos/" . $postData['imagen_actual'];
        if (file_exists($imagenAnterior)) {
            unlink($imagenAnterior);
        }
    } else {
        // Mantener la imagen actual si no se subió una nueva
        $nuevaImagen = $postData['imagen_actual'];
    }

    // Actualizar datos del producto
    $producto->edit(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $nuevaImagen, // Imagen actualizada o anterior
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

    // Agregar alerta de éxito
    (new Alerta())->add_alerta('success', "Se editaron correctamente los datos del producto, incluida la imagen.");
    header('Location: ../index.php?sec=admin_productos');
} catch (Exception $e) {
    // Manejar errores
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado. Por favor, intente nuevamente.");
    header('Location: ../index.php?sec=admin_productos');
}
