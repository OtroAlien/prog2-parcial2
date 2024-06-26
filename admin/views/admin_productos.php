<?PHP
$productos = (new producto())->catalogo_completo();

// echo "<pre>";
// print_r($productos[0]);
// echo "</pre>";

?>
<div class="row my-5">
    <div class="col">

        <h1 class="text-center mb-5 fw-bold">Administración de Productos</h1>
        <div class="row mb-5 d-flex align-items-center">

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
                        <th scope="col">Producto Destacado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP foreach ($productos as $P) { ?>
                        <tr>
                            <td><img src="../img/products/<?= $P->getImagen() ?>" alt="Imagen de <?= $P->getNombre() ?>" class="img-fluid rounded shadow-sm"></td>
                            <td><?= $P->getNombre() ?></td>
                            <td><?= $P->getDescripcion() ?></td>
                            <td>$<?= $P->getPrecio() ?></td>
                            <td><?= $P->getStock() ?></td>
                            <td><?= $P->getCategoria() ?></td>
                            <td><?= $P->getLanzamiento() ?></td>
                            <td><?= $P->getContenido() ?> ml</td>
                            <td><?= $P->getDescuento() ?>%</td>
                            <td><?= $P->getWaterproof() ? 'Sí' : 'No' ?></td>
                            <td><?= $P->getVegano() ? 'Sí' : 'No' ?></td>
                            <td><?= $P->getProductoDestacado() ? 'Sí' : 'No' ?></td>
                            <td>
                                <a href="index.php?sec=edit_producto&id=<?= $P->getId() ?>" role="button" class="d-block btn btn-sm btn-warning mb-1">Editar</a>
                                <a href="index.php?sec=delete_producto&id=<?= $P->getId() ?>" role="button" class="d-block btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?PHP } ?>
                </tbody>
            </table>

            <a href="index.php?sec=add_producto" class="btn btn-primary mt-5"> Cargar nuevo Producto</a>
        </div>

    </div>
</div>
