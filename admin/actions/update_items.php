<?php
require_once "../../functions/autoload.php";

// Verificar si hay datos de cantidades enviados
if (isset($_POST['q']) && is_array($_POST['q'])) {
    // Actualizar las cantidades en el carrito
    (new Carrito())->update_quantities($_POST['q']);
    
    // Agregar mensaje de éxito
    (new Alerta())->add_alerta('success', "Las cantidades han sido actualizadas correctamente.");
} else {
    // Agregar mensaje de error
    (new Alerta())->add_alerta('danger', "No se recibieron datos para actualizar las cantidades.");
}

// Redirigir al carrito
header('location: ../../index.php?sec=carrito');
exit;