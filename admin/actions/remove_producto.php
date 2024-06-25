<?PHP
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? FALSE;

if($id){
    (new Producto())->remove_producto($id);
    header('location: ../../index.php?sec=carrito');
}