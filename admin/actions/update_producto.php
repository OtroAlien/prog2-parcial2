<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;


if(!empty($postData)){
    (new Producto())->update_quantities($postData['q']);
    header('location: ../../index.php?sec=carrito');
}