<?php
require_once(__DIR__ . '/../../functions/autoload.php');

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    
    $producto = (new Producto())->productoPorId($id);

    
    if (!empty($fileData['tmp_name'])) {
        $nombreProducto = isset($postData['nombre']) ? $postData['nombre'] : 'producto'; 
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreProducto)) . '.jpeg'; 

        $directorioDestino = __DIR__ . "/../../img/productos/";

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $rutaDestino = $directorioDestino . $nombreUnico;

        if (move_uploaded_file($fileData['tmp_name'], $rutaDestino)) {
            $nuevaImagen = 'productos/' . $nombreUnico;

            $imagenAnterior = __DIR__ . "/../../img/" . $postData['imagen_actual'];
            if (file_exists($imagenAnterior)) {
                unlink($imagenAnterior);
            }
        } else {
            throw new Exception("Hubo un error al mover la imagen.");
        }
    } else {
        $nuevaImagen = $postData['imagen_actual'];
    }

    $producto->edit(
        $postData['nombre'],
        $postData['descripcion'],
        $postData['precio'],
        $nuevaImagen, 
        $postData['stock'],
        $postData['categoria'],
        $postData['lanzamiento'],
        $postData['contenido'],
        $postData['descuento'],
        $postData['waterproof'],
        $postData['vegano'],
        isset($postData['productoDestacado']) ? $postData['productoDestacado'] : 0
    );
    // ✅ Guardar subcategorías seleccionadas
    if (!empty($postData['subcategorias']) && is_array($postData['subcategorias'])) {
        $producto->actualizarSubcategorias($postData['subcategorias']);
    }

    header('Location: ../index.php?sec=admin_productos&status=success');
    exit;
} catch (Exception $e) {
    header('Location: ../index.php?sec=admin_productos&status=error');
    exit;
}
?>
