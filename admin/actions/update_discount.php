<?php
require_once "../../functions/autoload.php";

// Verificar que se haya enviado el formulario y que exista un valor antiguo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['valor'])) {
    $valor_antiguo = $_GET['valor'];
    $valor_nuevo = $_POST['valor'] ?? '';
    
    try {
        // Validar que el valor no esté vacío
        if ($valor_nuevo === '') {
            throw new Exception("El valor del descuento no puede estar vacío.");
        }
        
        // Validar que sea un número entre 0 y 100
        if (!is_numeric($valor_nuevo) || $valor_nuevo < 0 || $valor_nuevo > 100) {
            throw new Exception("El descuento debe ser un número entre 0 y 100.");
        }
        
        $conexion = Conexion::getConexion();
        
        // Verificar si ya existe el nuevo valor de descuento (excepto el actual)
        $checkQuery = "SELECT COUNT(*) FROM descuentos WHERE valor = ? AND valor != ?";
        $checkStmt = $conexion->prepare($checkQuery);
        $checkStmt->execute([$valor_nuevo, $valor_antiguo]);
        if ($checkStmt->fetchColumn()) {
            throw new Exception("El descuento '$valor_nuevo%' ya existe.");
        }
        
        // Actualizar el descuento en la tabla descuentos
        $query = "UPDATE descuentos SET valor = :valor_nuevo WHERE valor = :valor_antiguo";
        $stmt = $conexion->prepare($query);
        $resultado = $stmt->execute([
            'valor_nuevo' => $valor_nuevo,
            'valor_antiguo' => $valor_antiguo
        ]);
        
        if ($resultado) {
            // También actualizar los productos que usen este descuento
            $updateProductsQuery = "UPDATE productos SET descuento = :valor_nuevo WHERE descuento = :valor_antiguo";
            $updateProductsStmt = $conexion->prepare($updateProductsQuery);
            $updateProductsStmt->execute([
                'valor_nuevo' => $valor_nuevo,
                'valor_antiguo' => $valor_antiguo
            ]);
            
            (new Alerta())->add_alerta('success', "Descuento actualizado correctamente.");
        } else {
            throw new Exception("Error al actualizar el descuento.");
        }
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', $e->getMessage());
    }
}

// Redireccionar de vuelta a la página de administración de productos
header('Location: ../index.php?sec=admin_productos');
exit;