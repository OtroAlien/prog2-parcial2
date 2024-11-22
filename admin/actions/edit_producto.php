<?php
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    
    $producto = (new Producto())->productoPorId($id);

    
    if (!empty($fileData['tmp_name'])) {
        // Generar un nombre único basado en el nombre del producto (o cualquier otra lógica que desees)
        $nombreProducto = isset($postData['nombre']) ? $postData['nombre'] : 'producto'; // Usar el nombre del producto
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreProducto)) . '.jpeg'; // Evitar espacios y poner .jpeg

        // Definir el directorio destino
        $directorioDestino = __DIR__ . "/../../img/productos/";

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Ruta completa de destino para la nueva imagen
        $rutaDestino = $directorioDestino . $nombreUnico;

        // Mover la imagen al directorio destino
        if (move_uploaded_file($fileData['tmp_name'], $rutaDestino)) {
            // La imagen se movió correctamente, ahora guardar la ruta relativa en la base de datos
            $nuevaImagen = 'productos/' . $nombreUnico;

            // Borrar la imagen anterior del servidor
            $imagenAnterior = __DIR__ . "/../../img/" . $postData['imagen_actual'];
            if (file_exists($imagenAnterior)) {
                unlink($imagenAnterior);
            }
        } else {
            throw new Exception("Hubo un error al mover la imagen.");
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

    // Redirigir después de actualizar
    header('Location: ../index.php?sec=admin_productos&status=success');
    exit;
} catch (Exception $e) {
    // Redirigir en caso de error
    header('Location: ../index.php?sec=admin_productos&status=error');
    exit;
}
?>
