<?php
require_once "../../functions/autoload.php";

// Verificar que se haya enviado el formulario y que exista un ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $categoria_id = $_GET['id'];
    $nombre = $_POST['nombre'] ?? '';
    
    try {
        // Validar que el nombre no esté vacío
        if (empty($nombre)) {
            throw new Exception("El nombre de la categoría no puede estar vacío.");
        }
        
        // Obtener la categoría existente
        $categoria = Categoria::categoriaPorId($categoria_id);
        
        if (!$categoria) {
            throw new Exception("La categoría no existe.");
        }
        
        // Guardar el nombre antiguo para actualizar productos
        $nombre_antiguo = $categoria->getNombre();
        
        // Actualizar la categoría
        $categoria->setNombre($nombre);
        $resultado = $categoria->actualizar();
        
        if ($resultado) {
            // Actualizar todos los productos que usan esta categoría
            $conexion = Conexion::getConexion();
            $query = "UPDATE productos SET categoria = :nombre_nuevo WHERE categoria = :nombre_antiguo";
            $stmt = $conexion->prepare($query);
            $stmt->execute([
                'nombre_nuevo' => $nombre,
                'nombre_antiguo' => $nombre_antiguo
            ]);
            
            (new Alerta())->add_alerta('success', "Categoría actualizada correctamente.");
        } else {
            throw new Exception("Error al actualizar la categoría.");
        }
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', $e->getMessage());
    }
}

// Redireccionar de vuelta a la página de administración de categorías
header('Location: ../index.php?sec=admin_productos');
exit;