<?php
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;

try {
    if (!$id) {
        throw new Exception("ID de categoría no proporcionado.");
    }
    
    $resultado = Producto::eliminarCategoriaConReasignacion($id);
    
    if ($resultado) {
        (new Alerta())->add_alerta('success', "Categoría eliminada correctamente.");
    } else {
        throw new Exception("Error al eliminar la categoría.");
    }
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', $e->getMessage());
}

header('Location: ../index.php?sec=admin_productos');
exit;