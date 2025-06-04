<?php
$id = $_GET['id'] ?? false;
$producto = (new Producto())->productoPorId($id);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">¿Está seguro que desea duplicar este producto?</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h3 class="fs-6">Nombre del Producto:</h3>
                    <p><?= $producto->getNombre() ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-grid gap-2">
                        <a href="actions/duplicate_product.php?id=<?= $producto->getId() ?>" role="button" class="btn btn-success btn-lg">Duplicar Producto</a>
                        <a href="index.php?sec=admin_productos" role="button" class="btn btn-secondary btn-lg mt-3">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>