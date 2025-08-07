<?php
require_once "../../functions/autoload.php";

$postData = $_POST;
$productoID = $postData['producto_id'] ?? false;
$cantidad = $postData['cantidad'] ?? 1;

if ($productoID) {
    // Obtener user_id si está logueado
    $user_id = $_SESSION['loggedIn']['id'] ?? null;
    
    // Crear instancia del carrito
    $carrito = new Carrito($user_id);
    
    // Agregar producto
    $carrito->add_item($productoID, $cantidad);
    
    (new Alerta())->add_alerta('success', "Producto agregado al carrito correctamente.");
} else {
    (new Alerta())->add_alerta('danger', "Error al agregar el producto al carrito.");
}

header('location: ../../index.php?sec=productos');
exit;
