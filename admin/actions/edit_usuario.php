<?php
require_once "../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'] ?? FALSE;
// $id = $_GET['id'] ?? FALSE;

$id = $_SESSION['loggedIn']['id'] ?? false;
try {
    // Cargar el usuario usando el método adecuado
    $usuario = (new Usuario())->obtenerIdUsuario($id);
    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
    }

    // Procesar la imagen si se ha cargado una nueva
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

            // Eliminar la imagen anterior si existe
            $imagenAnterior = __DIR__ . "/../../img/" . $postData['imagen_actual'];
            if (file_exists($imagenAnterior)) {
                unlink($imagenAnterior);
            }
        } else {
            throw new Exception("Hubo un error al mover la imagen.");
        }
    } else {
        // Si no se subió una imagen, mantener la imagen actual
        $nuevaImagen = $postData['imagen_actual'];
    }

    // Modificar los datos del usuario y la dirección
    $usuario->edit_usuario(
        [
            'username' => $postData['username'],
            'email' => $postData['email'],
            'nombre_completo' => $postData['nombre_completo'],
            'rol' => $postData['rol'],
            'foto_perfil' => $nuevaImagen
        ],
        [
            'calle' => $postData['calle'],
            'ciudad' => $postData['ciudad'],
            'localidad' => $postData['localidad'],
            'codigo_postal' => $postData['codigo_postal'],
            'pais' => $postData['pais'],
            'telefono' => $postData['telefono'],
            'altura' => $postData['altura']
        ]
    );

    // Redirigir a la página de éxito
    header('Location: ../index.php?sec=panel_usuario&status=success');
    exit;
} catch (Exception $e) {
    // Redirigir a la página con error si ocurre una excepción
    header('Location: ../index.php?sec=panel_usuario&status=error&message=' . urlencode($e->getMessage()));
    exit;
}
