<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;

$login = (new Autenticacion())->log_in($postData['email'], $postData['password']);

if ($login) {

    if($login == "usuario"){ 
        header('location: ../../index.php?sec=panel_usuario');
    }else{
        header('location: ../index.php?sec=dashboard');
    }
    
} else {
    header('location: ../index.php?sec=login');
}
