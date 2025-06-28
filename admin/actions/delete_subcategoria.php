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
        // Redirigir con éxito
        (new Alerta())->add_alerta('success', "Subcategoría eliminada correctamente.");
        header('Location: ../index.php?sec=admin_productos');
        exit;
    } else {
        throw new Exception("Error al eliminar la subcategoría.");
    }
    
} catch (Exception $e) {
    (new Alerta())->add_alerta('danger', $e->getMessage());
    header('Location: ../index.php?sec=admin_productos');
    exit;
}
?>