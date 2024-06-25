<?PHP
require_once "../../functions/autoload.php";

$items = (new Carrito())->get_carrito();
$userID = $_SESSION['loggedIn']['id'] ?? FALSE;

//  echo "<pre>";
//  print_r($items);
//  echo "</pre>";


try {

    if ($userID) {

        $datosCompra = [
            "id_usuario" => $userID,
            "fecha" => date("Y-m-d"),
            "importe" => (new Carrito())->precio_total()
        ];

        $detalleCompra = [];

        foreach ($items as $key => $value) {
            $detalleCompra[$key] = $value['cantidad'];
        }

        echo "<pre>";
        print_r($datosCompra);
        echo "</pre>";

        echo "<pre>";
        print_r($detalleCompra);
        echo "</pre>";

        (new Checkout())->insert_checkout_data($datosCompra, $detalleCompra);
        (new Carrito())->clear_items();

        (new Alerta())->add_alerta('success', "Su compra se realizó correctamente, nos estaremos contactando por mail para pactar el envio");
        header('location: ../../index.php?sec=panel_usuario');


    }else{
        (new Alerta())->add_alerta('warning', "Su sessión expiró. Por favor, ingrese nuevamente");
        header('location: ../../index.php?sec=login');
    }

}catch (Exception $e) {
    // echo "<pre>";
    // print_r($e);
    // echo "</pre>";
    (new Alerta())->add_alerta('warning', "No se pudo finalizar la compra");
    header('location: ../../index.php?sec=panel_usuario');
}