<?php
require_once "../../functions/autoload.php";

try {
    $id = $_GET['id'] ?? false;

    if (!$id) {
        throw new Exception("ID de subcategoría no proporcionado.");
    }

    $subcategoria = Subcategoria::subcategoriaPorId($id);

    if (!$subcategoria) {
        throw new Exception("Subcategoría no encontrada.");
    }

    if ($subcategoria->eliminar()) {
        header('Location: ../index.php?sec=admin_subcategorias&msg=eliminado');
        exit;
    } else {
        throw new Exception("Error al eliminar la subcategoría.");
    }

} catch (Exception $e) {
    header('Location: ../index.php?sec=admin_subcategorias&error=' . urlencode($e->getMessage()));
    exit;
}
