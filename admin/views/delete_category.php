<?php
$id = $_GET['id'] ?? false;
$categoria = (new Categoria())->categoriaPorId($id); 

$cantidadProductos = Categoria::contarProductosPorCategoria($id);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">¿Está seguro que desea eliminar esta categoría?</h2>
            
            <?php if ($cantidadProductos > 0): ?>
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">¡Advertencia!</h4>
                    <p>Esta categoría tiene <strong><?= $cantidadProductos ?> producto<?= $cantidadProductos > 1 ? 's' : '' ?></strong> asociado<?= $cantidadProductos > 1 ? 's' : '' ?>.</p>
                    <p>Si elimina esta categoría, todos los productos asociados serán reasignados a la categoría "Sin categoría".</p>
                    <hr>
                    <p class="mb-0">¿Está seguro que desea continuar?</p>
                </div>
            <?php endif; ?>
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h3 class="fs-6">Nombre de la categoría:</h3>
                    <p><?= htmlspecialchars($categoria->getNombre()) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-grid gap-2">
                        <a href="actions/delete_category.php?id=<?= $categoria->getId() ?>" role="button" class="btn btn-danger btn-lg">
                            <?php if ($cantidadProductos > 0): ?>
                                Eliminar y Reasignar Productos
                            <?php else: ?>
                                Eliminar Categoría
                            <?php endif; ?>
                        </a>
                        <a href="index.php?sec=admin_productos" role="button" class="btn btn-secondary btn-lg mt-3">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>