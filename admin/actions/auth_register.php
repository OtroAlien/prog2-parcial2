<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;

$register = (new Autenticacion())->register($postData['email'], $postData['pswd']);

if ($register) {

    if($register == "usuario"){ 
        header('location: ../../index.php?sec=panel_usuario');
    }else{
        header('location: ../index.php?sec=dashboard');
    }
    
} else {
    header('location: ../index.php?sec=login');
}