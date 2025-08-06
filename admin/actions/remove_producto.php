<?php
require_once "../../functions/autoload.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$productoID = $_GET['id'] ?? null;

if (!$productoID || !is_numeric($productoID)) {
    (new Alerta())->add_alerta('danger', "No se especificó un producto válido para eliminar.");
    header('Location: ../../index.php?sec=carrito');
    exit;
}

// Obtener ID del usuario si está logueado
$userId = null;
if (isset($_SESSION['loggedIn']) && is_array($_SESSION['loggedIn'])) {
    $userId = $_SESSION['loggedIn']['id'] ?? null;
}

try {
    $carrito = new Carrito($userId);
    $carrito->remove_item((int)$productoID);
    (new Alerta())->add_alerta('success', "Producto eliminado del carrito correctamente.");
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Error al eliminar el producto: " . $e->getMessage());
}

header('Location: ../../index.php?sec=carrito');
exit;