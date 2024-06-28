<?php
require_once "../../functions/autoload.php";

$postData = $_POST;

$auth = new Autenticacion();
$rol = $auth->log_in($postData['email'], $postData['pswd']);

if ($rol) {
    if ($rol == "usuario") {
        header('location: ../../index.php?sec=panel_usuario');
    } elseif ($rol == "admin" || $rol == "superadmin") {
        header('location: ../index.php?sec=dashboard');
    } else {
        header('location: ../../index.php?sec=login');
    }
} else {
    header('location: ../../index.php?sec=login');
}
?>