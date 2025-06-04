<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID inválido");
}

$producto = (new Producto())->productoPorId($id);
if (!$producto) {
    die("Producto no encontrado");
}

// Obtener todas las categorías para el dropdown
$categorias = Categoria::obtenerTodas();
?>

<div class="container my-5">
    <div class="row">
        <div class="col">
            <h2 class="text-center mb-5 fw-bold">
                Edición de datos de: <span class="text-danger"><?= htmlspecialchars($producto->getNombre()) ?></span>
            </h2>
            <div class="row mb-5 d-flex justify-content-center mt-5">
                <form class="row g-3"
                      action="actions/edit_producto.php?id=<?= htmlspecialchars($producto->getId()) ?>"
                      method="POST"
                      enctype="multipart/form-data">

                    <div class="col-md-6 mt-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                               value="<?= htmlspecialchars($producto->getNombre()) ?>"
                               placeholder="Ingresa el nombre del producto">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" id="categoria" required>
                            <option value="" disabled>Elija una opción</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria->getId()) ?>" <?= $producto->getCategoriaId() == $categoria->getId() ? "selected" : "" ?>>
                                    <?= htmlspecialchars($categoria->getNombre()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required
                                  placeholder="Escribe una descripción para el producto"><?= htmlspecialchars($producto->getDescripcion()) ?></textarea>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" required
                               value="<?= htmlspecialchars($producto->getPrecio()) ?>"
                               placeholder="Precio en formato numérico">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required
                               value="<?= htmlspecialchars($producto->getStock()) ?>"
                               placeholder="Cantidad disponible">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="lanzamiento" class="form-label">Lanzamiento</label>
                        <input type="date" class="form-control" id="lanzamiento" name="lanzamiento" required
                               value="<?= htmlspecialchars($producto->getLanzamiento()) ?>">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="contenido" class="form-label">Contenido (ml)</label>
                        <select class="form-select" name="contenido" id="contenido" required>
                            <option value="" disabled>Elija una opción</option>
                            <option value="15" <?= $producto->getContenido() == "15" ? "selected" : "" ?>>15 ml</option>
                            <option value="30" <?= $producto->getContenido() == "30" ? "selected" : "" ?>>30 ml</option>
                            <option value="60" <?= $producto->getContenido() == "60" ? "selected" : "" ?>>60 ml</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="descuento" class="form-label">Descuento (%)</label>
                        <select class="form-select" name="descuento" id="descuento" required>
                            <option value="" disabled>Elija una opción</option>
                            <option value="0" <?= $producto->getDescuento() == "0" ? "selected" : "" ?>>0%</option>
                            <option value="15" <?= $producto->getDescuento() == "15" ? "selected" : "" ?>>15%</option>
                            <option value="20" <?= $producto->getDescuento() == "20" ? "selected" : "" ?>>20%</option>
                            <option value="40" <?= $producto->getDescuento() == "40" ? "selected" : "" ?>>40%</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="imagen_actual" class="form-label">Imagen Actual</label>
                        <img src="../img/<?= htmlspecialchars($producto->getImagen()) ?>"
                             alt="Imagen de <?= htmlspecialchars($producto->getNombre()) ?>"
                             class="img-fluid rounded shadow-sm d-block mb-3">
                        <input class="form-control" type="hidden" id="imagen_actual" name="imagen_actual"
                               value="<?= htmlspecialchars($producto->getImagen()) ?>">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="imagen" class="form-label">Reemplazar Imagen</label>
                        <input class="form-control" type="file" id="imagen" name="imagen">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="waterproof" class="form-label">Waterproof</label>
                        <select class="form-select" name="waterproof" id="waterproof" required>
                            <option value="1" <?= $producto->getWaterproof() == 1 ? "selected" : "" ?>>Sí</option>
                            <option value="0" <?= $producto->getWaterproof() == 0 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="vegano" class="form-label">Vegano</label>
                        <select class="form-select" name="vegano" id="vegano" required>
                            <option value="1" <?= $producto->getVegano() == 1 ? "selected" : "" ?>>Sí</option>
                            <option value="0" <?= $producto->getVegano() == 0 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="productoDestacado" class="form-label">Producto Destacado</label>
                        <select class="form-select" name="productoDestacado" id="productoDestacado" required>
                            <option value="1" <?= $producto->getDestacado() == 1 ? "selected" : "" ?>>Sí</option>
                            <option value="0" <?= $producto->getDestacado() == 0 ? "selected" : "" ?>>No</option>
                        </select>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success btn-sm">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="history.back()">Volver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
