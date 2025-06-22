<?php
require_once "../../functions/autoload.php";

// Asegurarse de que la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Redirigir al login si no está logueado
    header('Location: ../../index.php?sec=login');
    exit;
}

// Obtener los datos del formulario
$productoID = $_POST['producto_id'] ?? $_GET['producto_id'] ?? null;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

// Validar que se recibió un ID de producto
if (!$productoID || !is_numeric($productoID)) {
    (new Alerta())->add_alerta('danger', "No se especificó un producto válido para agregar al carrito.");
    header('Location: ../../index.php?sec=productos');
    exit;
}

// Agregar el producto al carrito
try {
    $carrito = new Carrito();
    $carrito->add_item((int)$productoID, $cantidad);
    (new Alerta())->add_alerta('success', "Producto agregado al carrito correctamente.");
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Ocurrió un error al agregar el producto al carrito.");
}

// Redirigir al carrito
header('Location: ../../index.php?sec=carrito');
exit;