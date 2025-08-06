<?php
require_once "../../functions/autoload.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay datos de cantidades enviados
if (isset($_POST['q']) && is_array($_POST['q'])) {
    // Obtener ID del usuario si está logueado
    $userId = null;
    if (isset($_SESSION['loggedIn']) && is_array($_SESSION['loggedIn'])) {
        $userId = $_SESSION['loggedIn']['user_id'] ?? $_SESSION['loggedIn']['id'] ?? null;
    }
    
    // Actualizar las cantidades en el carrito
    $carrito = new Carrito($userId);
    $carrito->update_quantities($_POST['q']);
    
    // Agregar mensaje de éxito
    (new Alerta())->add_alerta('success', "Las cantidades han sido actualizadas correctamente.");
} else {
    // Agregar mensaje de error
    (new Alerta())->add_alerta('danger', "No se recibieron datos para actualizar las cantidades.");
}

// Redirigir al carrito
header('location: ../../index.php?sec=carrito');
exit;