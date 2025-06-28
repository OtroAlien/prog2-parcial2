<?php
$id = $_GET['id'] ?? false;
$subcategoria = (new Subcategoria())->subcategoriaPorId($id);
$cantidadProductos = Subcategoria::contarProductosPorSubcategoria($id);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">¿Está seguro que desea eliminar esta subcategoría?</h2>
            
            <?php if ($cantidadProductos > 0): ?>
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">¡Advertencia!</h4>
                    <p>Esta subcategoría está asociada a <strong><?= $cantidadProductos ?> producto<?= $cantidadProductos > 1 ? 's' : '' ?></strong>.</p>
                    <p>Si la elimina, se eliminarán también las asociaciones en la base de datos, pero los productos no se eliminarán.</p>
                    <hr>
                    <p class="mb-0">¿Desea continuar con la eliminación?</p>
                </div>
            <?php endif; ?>
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h3 class="fs-6">Nombre de la subcategoría:</h3>
                    <p><?= htmlspecialchars($subcategoria->getNombre()) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-grid gap-2">
                        <a href="actions/delete_subcategoria.php?id=<?= $subcategoria->getId() ?>" role="button" class="btn btn-danger btn-lg">
                            <?php if ($cantidadProductos > 0): ?>
                                Eliminar y Desvincular
                            <?php else: ?>
                                Eliminar Subcategoría
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
