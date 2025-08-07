<?php
require_once "../../functions/autoload.php";

$postData = $_POST;

$auth = new Autenticacion();
$rol = $auth->log_in($postData['email'], $postData['pswd']);

if ($rol) {
    if ($rol == "usuario") {
        header('location: ../../index.php?sec=panel_usuario');
        exit;
    } elseif ($rol == "admin" || $rol == "superadmin") {
        header('location: ../../admin/index.php');
        exit;
    }
}

// Si llegamos aquí, el login falló
header('location: ../../index.php?sec=login');
exit;
