<?php
require_once "../../functions/autoload.php";

try {
    $id = $_GET['id'] ?? false;
    $nombre = $_POST['nombre'] ?? '';
    
    if (!$id) {
        throw new Exception("ID de subcategoría no proporcionado.");
    }
    
    if (empty($nombre)) {
        throw new Exception("El nombre de la subcategoría es requerido.");
    }
    
    $subcategoria = Subcategoria::subcategoriaPorId($id);
    
    if (!$subcategoria) {
        throw new Exception("Subcategoría no encontrada.");
    }
    
    $subcategoria->setNombre($nombre);
    
    if ($subcategoria->actualizar()) {
        // Redirigir con éxito
        header('Location: ../index.php?sec=admin_productos');
        exit;
    } else {
        throw new Exception("Error al actualizar la subcategoría.");
    }
    
} catch (Exception $e) {
    // Si querés implementar alertas tipo flash, podrías usar tu clase Alerta aquí
    // (new Alerta())->add_alerta('danger', "Error al actualizar la subcategoría: " . $e->getMessage());
    header('Location: ../index.php?sec=admin_productos');
    exit;
}
?>