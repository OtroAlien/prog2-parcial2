<?php
require_once "../../functions/autoload.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedIn'])) {
    // Redirigir al login si no está logueado
    header('location: ../../index.php?sec=login');
    exit;
}

// Obtener los datos del formulario
$productoID = $_POST['producto_id'] ?? $_GET['producto_id'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;

// Validar que se recibió un ID de producto
if (!$productoID) {
    (new Alerta())->add_alerta('danger', "No se especificó un producto para agregar al carrito.");
    header('location: ../../index.php?sec=productos');
    exit;
}

// Agregar el producto al carrito
(new Carrito())->add_item($productoID, $cantidad);

// Redirigir al carrito
header('location: ../../index.php?sec=carrito');