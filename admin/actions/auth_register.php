<?php
require_once "../../functions/autoload.php";

// Obtener los datos del formulario
$postData = $_POST;
$username = $postData['name'];
$email = $postData['email'];
$password = $postData['pswd'];

// Verificar que todos los campos estén completos
if (empty($username) || empty($email) || empty($password)) {
    header('location: ../index.php?sec=register&error=emptyfields');
    exit();
}

// Verificar si el correo electrónico ya está registrado
$db = (new Database())->connect();
$query = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
$query->bindParam(':email', $email);
$query->execute();
if ($query->rowCount() > 0) {
    header('location: ../index.php?sec=register&error=emailtaken');
    exit();
}

// Hash de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar el nuevo usuario en la base de datos
$query = $db->prepare("INSERT INTO usuarios (username, nombre_completo, email, password_hash, adress, rol) VALUES (:username, :nombre_completo, :email, :password_hash, :adress, 'usuario')");
$query->bindParam(':username', $username);
$query->bindParam(':nombre_completo', $nombre_completo);
$query->bindParam(':email', $email);
$query->bindParam(':password_hash', $passwordHash);
$query->bindParam(':adress', $direccion);

if ($query->execute()) {
    header('location: ../index.php?sec=login&success=registered');
} else {
    header('location: ../index.php?sec=register&error=sqlerror');
}
