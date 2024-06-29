<?php

$productos = (new Producto())->catalogoCompleto();

// Verificar si se obtuvieron productos
if (!$productos) {
    echo "<p>No se encontraron productos.</p>";
    return;
}
?>

<div class="row my-5">
    <div class="col">
        <h1 class="text-center mb-5 fw-bold">Administración de Productos</h1>
        <div class="row mb-5 d-flex align-items-center">
        <a href="index.php?sec=add_producto" class="btn btn-primary mt-5"> Cargar nuevo Producto</a>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="15%">Imagen</th>
                        <th scope="col" width="15%">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Lanzamiento</th>
                        <th scope="col">Contenido (ml)</th>
                        <th scope="col">Descuento (%)</th>
                        <th scope="col">Waterproof</th>
                        <th scope="col">Vegano</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $P) { ?>
                        <tr>
                            <td><img src="../../img/productos/<?= htmlspecialchars($P->getImagen()) ?>" alt="Imagen de <?= htmlspecialchars($P->getNombre()) ?>" class="img-fluid rounded shadow-sm"></td>
                            <td><?= htmlspecialchars($P->getNombre()) ?></td>
                            <td><?= htmlspecialchars($P->getDescripcion()) ?></td>
                            <td>$<?= htmlspecialchars($P->getPrecio()) ?></td>
                            <td><?= htmlspecialchars($P->getStock()) ?></td>
                            <td><?= htmlspecialchars($P->getCategoria()) ?></td>
                            <td><?= htmlspecialchars($P->getLanzamiento()) ?></td>
                            <td><?= htmlspecialchars($P->getContenido()) ?> ml</td>
                            <td><?= htmlspecialchars($P->getDescuento()) ?>%</td>
                            <td><?= $P->getWaterproof() ? 'Sí' : 'No' ?></td>
                            <td><?= $P->getVegano() ? 'Sí' : 'No' ?></td>
                            <td>
                                <a href="index.php?sec=edit_producto&id=<?= htmlspecialchars($P->getId()) ?>" role="button" class="d-block btn btn-sm btn-warning mb-1">Editar</a>
                                <a href="index.php?sec=delete_producto&id=<?= htmlspecialchars($P->getId()) ?>" role="button" class="d-block btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
