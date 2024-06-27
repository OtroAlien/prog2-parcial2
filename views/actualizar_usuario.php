<?php
session_start();
$userID = $_SESSION['loggedIn']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $conexion = Conexion::getConexion();
    $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([$nombre, $email, $userID]);

    $_SESSION['loggedIn']['nombre'] = $nombre;
    $_SESSION['loggedIn']['email'] = $email;

    header("Location: panel_usuario.php");
    exit();
}
?>
