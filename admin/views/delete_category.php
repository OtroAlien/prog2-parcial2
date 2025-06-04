<?php
// This file is already included by admin/index.php
// require_once "../../functions/autoload.php";

$id = $_GET['id'] ?? false;
$categoria = (new Categoria())->categoriaPorId($id); // Suponiendo que tenés este método
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">¿Está seguro que desea eliminar esta categoría?</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h3 class="fs-6">Nombre de la categoría:</h3>
                    <p><?= htmlspecialchars($categoria->getNombre()) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-grid gap-2">
                        <a href="actions/delete_categoria.php?id=<?= $categoria->getId() ?>" role="button" class="btn btn-danger btn-lg">
                            Eliminar Categoría
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