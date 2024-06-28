<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;

$usuario = $postData['username'];
$email = $postData['email'];
$password = $postData['pswd'];

$register = (new Autenticacion())->register($usuario, $password, $email,);

if ($register) {
    header('location: ../../index.php?sec=login');
} else {
    header('location: ../../index.php?sec=login');
}
exit();
