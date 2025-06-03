<?php
require_once "../../functions/autoload.php";

// Verificar que se haya enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    
    try {
        // Validar que el nombre no esté vacío
        if (empty($nombre)) {
            throw new Exception("El nombre de la categoría no puede estar vacío.");
        }
        
        // Agregar la nueva categoría
        $categoria_id = Categoria::agregarCategoria($nombre);
        
        if ($categoria_id) {
            (new Alerta())->add_alerta('success', "Categoría '$nombre' agregada correctamente.");
        } else {
            throw new Exception("Error al agregar la categoría.");
        }
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', $e->getMessage());
    }
}

// Redireccionar de vuelta a la página de administración de categorías
header('Location: ../index.php?sec=admin_categorias');
exit;