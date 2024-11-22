<?php
require_once "../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    // Obtener el usuario por ID
    $usuario = (new Usuario())->getId($id);
    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
    }

    if (!empty($fileData['tmp_name'])) {
        // Generar un nombre único para la imagen
        $nombreUsuario = isset($postData['username']) ? $postData['username'] : 'usuario';
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreUsuario)) . '.jpeg';

        // Definir el directorio destino
        $directorioDestino = __DIR__ . "/../../img/usuarios/";

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Ruta completa de destino para la nueva imagen
        $rutaDestino = $directorioDestino . $nombreUnico;

        // Mover la imagen al directorio destino
        if (move_uploaded_file($fileData['tmp_name'], $rutaDestino)) {
            // Guardar la ruta relativa de la nueva imagen
            $nuevaImagen = 'usuarios/' . $nombreUnico;

            // Borrar la imagen anterior
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

    // Actualizar datos del usuario
    $usuario->edit_usuario(
        $postData['username'],
        $postData['email'],
        $postData['nombre_completo'],
        $postData['rol'],
        $nuevaImagen
    );

    // Actualizar datos de dirección
    $usuario->edit_address([
        'calle' => $postData['calle'],
        'ciudad' => $postData['ciudad'],
        'localidad' => $postData['localidad'],
        'codigo_postal' => $postData['codigo_postal'],
        'pais' => $postData['pais'],
        'telefono' => $postData['telefono'],
        'altura' => $postData['altura']
    ]);

    // Redirigir en caso de éxito
    header('Location: ../index.php?sec=admin_usuarios&status=success');
    exit;
} catch (Exception $e) {
    // Redirigir en caso de error
    header('Location: ../index.php?sec=admin_usuarios&status=error&message=' . urlencode($e->getMessage()));
    exit;
}
