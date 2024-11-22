<?php
require_once "../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;

try {
    $usuario = (new Usuario())->getId($id);
    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
    }

    if (!empty($fileData['tmp_name'])) {
        $nombreUsuario = isset($postData['username']) ? $postData['username'] : 'usuario';
        $nombreUnico = strtolower(str_replace(' ', '-', $nombreUsuario)) . '.jpeg';

        $directorioDestino = __DIR__ . "/../../img/usuarios/";

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $rutaDestino = $directorioDestino . $nombreUnico;

        if (move_uploaded_file($fileData['tmp_name'], $rutaDestino)) {
            $nuevaImagen = 'usuarios/' . $nombreUnico;

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

    $usuario->edit_usuario(
        $postData['username'],
        $postData['email'],
        $postData['nombre_completo'],
        $postData['rol'],
        $nuevaImagen
    );

    $usuario->edit_address([
        'calle' => $postData['calle'],
        'ciudad' => $postData['ciudad'],
        'localidad' => $postData['localidad'],
        'codigo_postal' => $postData['codigo_postal'],
        'pais' => $postData['pais'],
        'telefono' => $postData['telefono'],
        'altura' => $postData['altura']
    ]);

    header('Location: ../index.php?sec=admin_usuarios&status=success');
    exit;
} catch (Exception $e) {
    header('Location: ../index.php?sec=admin_usuarios&status=error&message=' . urlencode($e->getMessage()));
    exit;
}
