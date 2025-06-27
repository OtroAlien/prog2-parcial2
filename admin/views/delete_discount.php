<?php
// This file is already included by admin/index.php
// require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;
$descuento = Producto::descuentoPorId($id);

// Verificar si el descuento tiene productos asociados
$cantidadProductos = Producto::contarProductosPorDescuento($id);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">¿Está seguro que desea eliminar este descuento?</h2>
            
            <?php if ($cantidadProductos > 0): ?>
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">¡Advertencia!</h4>
                    <p>Este descuento tiene <strong><?= $cantidadProductos ?> producto<?= $cantidadProductos > 1 ? 's' : '' ?></strong> asociado<?= $cantidadProductos > 1 ? 's' : '' ?>.</p>
                    <p>Si elimina este descuento, todos los productos asociados serán reasignados al descuento "Sin descuento".</p>
                    <hr>
                    <p class="mb-0">¿Está seguro que desea continuar?</p>
                </div>
            <?php endif; ?>
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h3 class="fs-6">Nombre del descuento:</h3>
                    <p><?= htmlspecialchars($descuento->nombre) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-grid gap-2">
                        <a href="actions/delete_discount.php?id=<?= $descuento->id ?>" role="button" class="btn btn-danger btn-lg">
                            <?php if ($cantidadProductos > 0): ?>
                                Eliminar y Reasignar Productos
                            <?php else: ?>
                                Eliminar Descuento
                            <?php endif; ?>
                        </a>
                        <a href="index.php?sec=admin_descuentos" role="button" class="btn btn-secondary btn-lg mt-3">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>