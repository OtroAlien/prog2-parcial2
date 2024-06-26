<?PHP
$id = $_GET['id'] ?? FALSE;
$producto = (new Producto())->producto_x_id($id);

?>


<div class="row my-5">
    <div class="col">

        <h1 class="text-center mb-5 fw-bold">Edición de datos de: <span class="text-danger"><?= $producto->getNombre() ?><span></h1>
        <div class="row mb-5 d-flex align-items-center">

            <form class="row g-3" action="actions/edit_producto_acc.php?id=<?= $producto->getId() ?>" method="POST" enctype="multipart/form-data">

                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= $producto->getNombre() ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" name="categoria" id="categoria" required>
                        <option value="" selected disabled>Elija una opción</option>
                        <option value="Skincare" <?= $producto->getCategoria() == "Skincare" ? "selected" : "" ?>>Skincare</option>
                        <option value="Maquillaje" <?= $producto->getCategoria() == "Maquillaje" ? "selected" : "" ?>>Maquillaje</option>
                        <option value="Cabello" <?= $producto->getCategoria() == "Cabello" ? "selected" : "" ?>>Cabello</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?= $producto->getDescripcion() ?></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" required value="<?= $producto->getPrecio() ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" required value="<?= $producto->getStock() ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lanzamiento" class="form-label">Lanzamiento</label>
                    <input type="date" class="form-control" id="lanzamiento" name="lanzamiento" required value="<?= $producto->getLanzamiento() ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contenido" class="form-label">Contenido (ml)</label>
                    <select class="form-select" name="contenido" id="contenido" required>
                        <option value="" selected disabled>Elija una opción</option>
                        <option value="15" <?= $producto->getContenido() == "15" ? "selected" : "" ?>>15 ml</option>
                        <option value="30" <?= $producto->getContenido() == "30" ? "selected" : "" ?>>30 ml</option>
                        <option value="60" <?= $producto->getContenido() == "60" ? "selected" : "" ?>>60 ml</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="descuento" class="form-label">Descuento (%)</label>
                    <select class="form-select" name="descuento" id="descuento" required>
                        <option value="" selected disabled>Elija una opción</option>
                        <option value="0" <?= $producto->getDescuento() == "0" ? "selected" : "" ?>>0%</option>
                        <option value="15" <?= $producto->getDescuento() == "15" ? "selected" : "" ?>>15%</option>
                        <option value="20" <?= $producto->getDescuento() == "20" ? "selected" : "" ?>>20%</option>
                        <option value="40" <?= $producto->getDescuento() == "40" ? "selected" : "" ?>>40%</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="imagen_actual" class="form-label">Imagen Actual</label>
                    <img src="../img/products/<?= $producto->getImagen() ?>" alt="Imagen de <?= $producto->getNombre() ?>" class="img-fluid rounded shadow-sm d-block">
                    <input class="form-control" type="hidden" id="imagen_actual" name="imagen_actual" required value="<?= $producto->getImagen() ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="imagen" class="form-label">Reemplazar Imagen</label>
                    <input class="form-control" type="file" id="imagen" name="imagen">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="waterproof" class="form-label">Waterproof</label>
                    <select class="form-select" name="waterproof" id="waterproof" required>
                        <option value="1" <?= $producto->getWaterproof() == 1 ? "selected" : "" ?>>Sí</option>
                        <option value="0" <?= $producto->getWaterproof() == 0 ? "selected" : "" ?>>No</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="vegano" class="form-label">Vegano</label>
                    <select class="form-select" name="vegano" id="vegano" required>
                        <option value="1" <?= $producto->getVegano() == 1 ? "selected" : "" ?>>Sí</option>
                        <option value="0" <?= $producto->getVegano() == 0 ? "selected" : "" ?>>No</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="producto_destacado" class="form-label">Producto Destacado</label>
                    <select class="form-select" name="producto_destacado" id="producto_destacado" required>
                        <option value="1" <?= $producto->getProductoDestacado() == 1 ? "selected" : "" ?>>Sí</option>
                        <option value="0" <?= $producto->getProductoDestacado() == 0 ? "selected" : "" ?>>No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Editar</button>
            </form>
        </div>
    </div>
</div>
