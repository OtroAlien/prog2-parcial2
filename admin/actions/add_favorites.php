<?PHP 
require_once "../../functions/autoload.php";

$postData = $_POST;
$datosArchivo = $_FILES['imagen'];
public function insert_like(int $producto_id, int $usuario_id){

    $conexion = Conexion::getConexion();
    $query = "INSERT INTO favoritos (usuario_id, producto_id) VALUES (:usuario_id, :producto_id)";

    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([
        "usuario_id" => $checkoutData['usuario_id'], 
        "producto_id" => $checkoutData['producto_id']
    ]);//al query no manda los datos aun que los hardcodees quizas si hay que pasarle id null
    //agregue el userdata en la linea 29 para que no hga falta pasarla por parametro
    //si le pasas self al boton le podes cambiar el estrilo
    //te quiero 


    // public function remove_like(int $productoID)
    // {
    //     if (isset($_SESSION['carrito'][$productoID])) {
    //         unset($_SESSION['carrito'][$productoID]);
    //     }
    // }

}
try {    

    $userData = $_SESSION['loggedIn'] ?? FALSE;
    insert_like($postData['product_id'],$postData['user_id'])

    (new Alerta())->add_alerta('success', "Añadido a favoritos");
    //  header('Location: ../index.php?sec=admin_personajes');

} catch (Exception $e) {
    //   echo "<pre>";
    //   print_r($e);
    //   echo "</pre>";
    //  die("No se pudo cargar el personaje =(");
    (new Alerta())->add_alerta('danger', "Ocurrió un error inesperado, por favor pongase en contacto con el administrador de sistema.");
    header('Location: ../index.php?sec=admin_personajes');
}