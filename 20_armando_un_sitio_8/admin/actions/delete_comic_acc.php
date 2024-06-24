<?PHP
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? FALSE;


try {

    $comic = (new Comic())->producto_x_id($id);

    (new Imagen())->borrarImagen(__DIR__ . "/../../img/covers/" . $comic->getPortada());
    $comic->clear_personajes_sec();
    $comic->delete();
    
    
    header('Location: ../index.php?sec=admin_comics');

}catch (Exception $e) {
    //  echo "<pre>";
    //  print_r($e->getMessage());
    //  echo "<pre>";
    //  die("No se pudo eliminar el Comic =(");
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_comics');
}