<?php
require_once "../../functions/autoload.php";

// Verificar si se recibió un ID
if (isset($_GET['id'])) {
    $productoID = $_GET['id'];
    
    // Eliminar el producto del carrito
    (new Carrito())->remove_item($productoID);
    
    // Agregar mensaje de éxito
    (new Alerta())->add_alerta('success', "El producto ha sido eliminado del carrito.");
} else {
    // Agregar mensaje de error
    (new Alerta())->add_alerta('danger', "No se especificó un producto para eliminar.");
}

// Redirigir al carrito
header('location: ../../index.php?sec=carrito');
exit;