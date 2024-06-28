<?PHP
require_once "../../functions/autoload.php";

$postData = $_POST;

$usuario = $postData['username'];
$email = $postData['email'];
$password = $postData['pswd'];

$register = (new Autenticacion())->register($usuario, $password, $email,);

if ($register) {
    header('Location: ../index.php?sec=login&success=registered');
} else {
    header('Location: ../index.php?sec=register&error=failed');
}
exit();
