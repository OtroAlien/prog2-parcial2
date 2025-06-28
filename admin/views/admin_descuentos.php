<?php
require_once "../functions/autoload.php";


$descuentos = Producto::obtenerTodosDescuentos();

$descuento_id = $_GET['descuento_id'] ?? null;
$descuento_seleccionado = null;

if ($descuento_id) {
    $descuento_seleccionado = Producto::descuentoPorId($descuento_id);
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Panel lateral para edición de descuentos -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Gestión de Descuentos</h5>
                </div>
                <div class="card-body">
                    <form action="../actions/<?= $descuento_seleccionado ? 'update_discount.php?id=' . $descuento_seleccionado->id : 'add_discount.php' ?>" method="POST">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor del Descuento (%)</label>
                            <input type="number" class="form-control" id="valor" name="valor" min="0" max="100" value="<?= $descuento_seleccionado ? htmlspecialchars($descuento_seleccionado->valor) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Descuento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $descuento_seleccionado ? htmlspecialchars($descuento_seleccionado->nombre) : '' ?>" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?= $descuento_seleccionado ? 'Actualizar Descuento' : 'Agregar Descuento' ?>
                            </button>
                            <?php if ($descuento_seleccionado): ?>
                                <a href="index.php?sec=admin_descuentos" class="btn btn-secondary">Cancelar Edición</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de descuentos -->
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">Administración de Descuentos</h2>
            <div class="d-flex justify-content-between mb-3">
                <a href="../admin/index.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Valor (%)</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($descuentos as $descuento): ?>
                            <tr>
                                <td><?= htmlspecialchars($descuento->id) ?></td>
                                <td><?= htmlspecialchars($descuento->valor) ?>%</td>
                                <td><?= htmlspecialchars($descuento->nombre) ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="index.php?sec=admin_descuentos&descuento_id=<?= $descuento->id ?>" class="btn btn-success btn-sm">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="index.php?sec=delete_discount&id=<?= $descuento->id ?>" class="btn btn-danger btn-sm">
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
