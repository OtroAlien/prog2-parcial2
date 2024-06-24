<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['portada'] ?? FALSE;
$id = $_GET['id'] ?? FALSE;


try {

    $comic = (new Comic())->producto_x_id($id);

    $comic->clear_personajes_sec();

    if (isset($postData['personajes_secundarios'])) {
        foreach ($postData['personajes_secundarios'] as $personaje_id) {
            $comic->add_personajes_sec($id, $personaje_id);
        }
    }

    if (!empty($fileData['tmp_name'])) {
        $portada = (new Imagen())->subirImagen(__DIR__ . "/../../img/covers", $fileData);
        (new Imagen())->borrarImagen(__DIR__ . "/../../img/covers/" . $postData['portada_og']);
    }else{
        $portada = $postData['portada_og'];
    }

    $comic->edit(
        $postData['titulo'],
        $postData['personaje_principal_id'],
        $postData['serie_id'],
        $postData['guionista_id'],
        $postData['artista_id'],
        $postData['volumen'],
        $postData['numero'],
        $postData['publicacion'],
        $postData['origen'],
        $postData['editorial'],
        $postData['bajada'],
        $postData['precio'],
        $portada
    );

    (new Alerta())->add_alerta('warning', "Se editaron correctamente los datos");
    header('Location: ../index.php?sec=admin_comics');

} catch (Exception $e) {
    // echo "<pre>";
    // print_r($e->getMessage());
    // echo "<pre>";
    // die("No se pudo editar el Comic =(");

    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_comics');
}