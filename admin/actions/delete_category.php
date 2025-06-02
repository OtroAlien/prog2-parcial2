<?php
require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;

try {
    if (!$id) {
        throw new Exception("ID de categoría no proporcionado.");
    }

    Producto::eliminarCategoriaConReasignacion($id);

    header('Location: ../index.php?sec=admin_categorias');
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', "Error al eliminar la categoría.");
    header('Location: ../index.php?sec=admin_categorias');
}
