<?PHP 
require_once "../../functions/autoload.php";

$postData = $_POST;
$datosArchivo = $_FILES['imagen'];

try {    
    $imagen = (new Imagen())->subirImagen(__DIR__ . "/../../img/personajes", $datosArchivo);
    
     (new Personaje())->insert(
         $postData['nombre'], 
         $postData['alias'], 
         $postData['creador'], 
         $postData['primera_aparicion'], 
         $postData['bio'], 
         $imagen
     );

     (new Alerta())->add_alerta('success', "El Personaje <strong>{$postData['nombre']} ({$postData['alias']})</strong> se cargó correctamente");
     header('Location: ../index.php?sec=admin_personajes');
 
 } catch (Exception $e) {
    //   echo "<pre>";
    //   print_r($e);
    //   echo "</pre>";
    //  die("No se pudo cargar el personaje =(");
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_personajes');
 }