<?php
require_once "../../functions/autoload.php";

$postData = $_POST;

$usuario = $postData['username'];
$email = $postData['email'];
$password = $postData['pswd'];
$nombre_completo = $postData['nombre_completo'];
$adress = $postData['adress'];

$register = (new Autenticacion())->register($usuario, $password, $email, $nombre_completo, $adress);

if ($register) {
    header('location: ../../index.php?sec=login');
} else {
    header('location: ../../index.php?sec=login');
}
exit();
