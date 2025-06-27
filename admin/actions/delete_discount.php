<?php
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;

try {
    if (!$id) {
        throw new Exception("ID de descuento no proporcionado.");
    }

    // Usar el método de la clase Producto para eliminar con reasignación
    Producto::eliminarDescuentoConReasignacion($id);

    // Redirigir con éxito
    header('Location: ../index.php?sec=admin_descuentos');
} catch (Exception $e) {
    // Si querés implementar alertas tipo flash, podrías usar tu clase Alerta aquí
    // (new Alerta())->add_alerta('danger', "Error al eliminar el descuento.");
    header('Location: ../index.php?sec=admin_descuentos');
}
