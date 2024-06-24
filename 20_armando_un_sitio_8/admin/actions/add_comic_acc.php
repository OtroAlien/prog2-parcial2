<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;
$fileData = $_FILES['portada'];

echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<pre>";
print_r($_FILES);
echo "</pre>";

try {

    $comic = new Comic();

    $portada = (new Imagen())->subirImagen(__DIR__ . "/../../img/covers", $fileData);

    $idComic = $comic->insert(
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
        $portada,
        $postData['precio']
    );

    if (isset($postData['personajes_secundarios'])) {
        foreach ($postData['personajes_secundarios'] as $personaje_id) {
            $comic->add_personajes_sec($idComic, $personaje_id);
        }
    }


    header('Location: ../index.php?sec=admin_comics');

} catch (Exception $e) {
    echo "<pre>";
    print_r($e->getMessage());
    echo "<pre>";
    die("No se pudo cargar el Comic =(");
}
