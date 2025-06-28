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
        // ✅ CAMBIO: redirige a admin_productos
        header('Location: ../index.php?sec=admin_productos&msg=eliminado');
        exit;
    } else {
        throw new Exception("Error al eliminar la subcategoría.");
    }

} catch (Exception $e) {
    // ✅ CAMBIO: redirige a admin_productos en caso de error también
    header('Location: ../index.php?sec=admin_productos&error=' . urlencode($e->getMessage()));
    exit;
}
