<?PHP
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? FALSE;

try {
    $personaje = (new Personaje)->get_x_id($id);
    $personaje->delete();
    if (!empty($personaje->getImagen())) {
        (new Imagen())->borrarImagen(__DIR__ . "/../../img/personajes/" . $personaje->getImagen());
    }
    
    (new Alerta())->add_alerta('danger', "Se eliminó correctamente el personaje " . $personaje->getTitulo());
    header('Location: ../index.php?sec=admin_personajes');
} catch (Exception $e) {
    // die("No se pudo eliminar el personaje =(");
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_comics');
}
