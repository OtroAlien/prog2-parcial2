<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['imagen'];
$id = $_GET['id'] ?? FALSE;

// echo "<pre>";
// print_r($postData);
// echo "</pre>";

//  echo "<pre>";
//  print_r($fileData);
//  echo "</pre>";

//  echo "<pre>";
//  print_r($id);
//  echo "</pre>";

try {
    $personaje = (new Personaje)->get_x_id($id);

    if (!empty($fileData['tmp_name'])) {
        //EL USUARIO DECIDIÓ REEMPLAZAR LA IMÁGEN

        $imagen = (new Imagen())->subirImagen(__DIR__ . "/../../img/personajes", $fileData);

        if (!empty($postData['imagen_og'])) {
            (new Imagen())->borrarImagen(__DIR__ . "/../../img/personajes/" . $postData['imagen_og']);
        }

    } else {
        $imagen = $postData['imagen_og'];
        //EL USUARIO DECIDIÓ QUEDARSE CON LA IMÁGEN ORIGINAL
    }

    echo "<pre>";
    print_r($personaje);
    echo "</pre>";


    $personaje->edit(
        $postData['nombre'],
        $postData['alias'],
        $postData['creador'],
        $postData['primera_aparicion'],
        $postData['bio'],
        $imagen
    );

    (new Alerta())->add_alerta('warning', "Se editaron correctamente los datos");
    header('Location: ../index.php?sec=admin_personajes');
} catch (Exception $e) {
    // echo "<pre>";
    // print_r($e);
    // echo "</pre>";
    // die("No se pudo editar el personaje =(");

    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_personajes');
}
