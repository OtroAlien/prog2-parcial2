<?php
require_once(__DIR__ . '/../../functions/autoload.php');



$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID inválido");
}

$producto = (new Producto())->productoPorId($id);
if (!$producto) {
    die("Producto no encontrado");
}

$categorias = Categoria::obtenerTodas();
$contenidos = Producto::obtenerTodosContenidos();
$descuentos = Producto::obtenerTodosDescuentos();

// Subcategorías
$subcategorias = Subcategoria::obtenerTodas(); // Clase que deberías tener
$subcategoriasSeleccionadas = $producto->getSubcategorias(); // Array de strings
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
    <label class="form-label">Subcategorías</label>
    <div class="d-flex flex-wrap gap-3">
        <?php foreach ($subcategorias as $sub): ?>
            <?php
            // Extraer los IDs de las subcategorías seleccionadas para comparar
            $idsSeleccionadas = array_map(fn($s) => $s->getId(), $subcategoriasSeleccionadas);
            ?>
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       id="subcat_<?= $sub->getId() ?>"
                       name="subcategorias[]"
                       value="<?= $sub->getId() ?>"
                       <?= in_array($sub->getId(), $idsSeleccionadas) ? 'checked' : '' ?>>
                <label class="form-check-label" for="subcat_<?= $sub->getId() ?>">
                    <?= htmlspecialchars($sub->getNombre()) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
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
                            <?php foreach ($contenidos as $contenido): ?>
                                <option value="<?= $contenido->valor ?>" <?= $producto->getContenido() == $contenido->valor ? "selected" : "" ?>>
                                    <?= htmlspecialchars($contenido->nombre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="descuento" class="form-label">Descuento (%)</label>
                        <select class="form-select" name="descuento" id="descuento" required>
                            <option value="" disabled>Elija una opción</option>
                            <?php foreach ($descuentos as $descuento): ?>
                                <option value="<?= $descuento->id ?>" <?= $producto->getDescuento() == $descuento->id ? "selected" : "" ?>>
                                    <?= htmlspecialchars($descuento->valor) ?>%
                                </option>
                            <?php endforeach; ?>
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
