<?php
require_once "../../functions/autoload.php";

try {
    $nombre = $_POST['nombre'] ?? '';
    
    if (empty($nombre)) {
        throw new Exception("El nombre de la subcategoría es requerido.");
    }
    
    $subcategoria = new Subcategoria();
    $subcategoria->setNombre($nombre);
    
    if ($subcategoria->crear()) {
        // Redirigir con éxito
        header('Location: ../index.php?sec=admin_productos');
        exit;
    } else {
        throw new Exception("Error al crear la subcategoría.");
    }
    
} catch (Exception $e) {
    // Si querés implementar alertas tipo flash, podrías usar tu clase Alerta aquí
    // (new Alerta())->add_alerta('danger', "Error al crear la subcategoría: " . $e->getMessage());
    header('Location: ../index.php?sec=admin_subcategorias');
}
?>