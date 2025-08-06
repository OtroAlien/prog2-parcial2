<?php
require_once "../../functions/autoload.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID del usuario si está logueado
$userId = null;
if (isset($_SESSION['loggedIn']) && is_array($_SESSION['loggedIn'])) {
    $userId = $_SESSION['loggedIn']['id'] ?? null;
}

try {
    $carrito = new Carrito($userId);
    $carrito->clear_items();
    (new Alerta())->add_alerta('success', "El carrito ha sido vaciado correctamente.");
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Error al vaciar el carrito: " . $e->getMessage());
}

header('location: ../../index.php?sec=carrito');
exit;
