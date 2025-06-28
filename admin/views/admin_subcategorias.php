<?php
require_once "../functions/autoload.php";

$subcategorias = Producto::obtenerTodasSubcategorias();

$subcategoria_id = $_GET['subcategoria_id'] ?? null;
$subcategoria_seleccionada = null;

if ($subcategoria_id) {
    $subcategoria_seleccionada = Producto::subcategoriaPorId($subcategoria_id);
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Panel lateral para edición de subcategorías -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Gestión de Subcategorías</h5>
                </div>
                <div class="card-body">
                    <form action="../actions/<?= $subcategoria_seleccionada ? 'update_subcategoria.php?id=' . $subcategoria_seleccionada['id'] : 'add_subcategoria.php' ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Subcategoría</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $subcategoria_seleccionada ? htmlspecialchars($subcategoria_seleccionada['nombre']) : '' ?>" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?= $subcategoria_seleccionada ? 'Actualizar Subcategoría' : 'Agregar Subcategoría' ?>
                            </button>
                            <?php if ($subcategoria_seleccionada): ?>
                                <a href="index.php?sec=admin_subcategorias" class="btn btn-secondary">Cancelar Edición</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de subcategorías -->
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">Administración de Subcategorías</h2>
            <div class="d-flex justify-content-between mb-3">
                <a href="../admin/index.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Productos Asociados</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subcategorias as $sub): 
                            $cantidadProductos = Producto::contarProductosPorSubcategoria($sub['id']);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($sub['id']) ?></td>
                                <td><?= htmlspecialchars($sub['nombre']) ?></td>
                                <td><span class="badge bg-info"><?= $cantidadProductos ?> producto<?= $cantidadProductos != 1 ? 's' : '' ?></span></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?sec=admin_subcategorias&subcategoria_id=<?= $sub['id'] ?>" class="btn btn-success btn-sm">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="index.php?sec=delete_subcategoria&id=<?= $sub['id'] ?>" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
