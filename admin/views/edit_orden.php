<?php
require_once "../functions/autoload.php";

// Verificar si se proporcionó un ID
$orden_id = $_GET['id'] ?? null;

if (!$orden_id) {
    echo "<div class='alert alert-danger'>ID de orden no proporcionado</div>";
    header("Location: index.php?sec=admin_ordenes");
    exit;
}

// Obtener la información de la orden
$orden = null;
try {
    $orden = (new Compra())->obtenerOrdenPorId($orden_id);
    
    if (!$orden) {
        echo "<div class='alert alert-danger'>Orden no encontrada</div>";
        header("Location: index.php?sec=admin_ordenes");
        exit;
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error al cargar la orden: " . $e->getMessage() . "</div>";
    exit;
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4 fw-bold">Editar Orden #<?= htmlspecialchars($orden_id) ?></h2>
            
            <div class="d-flex justify-content-between mb-4">
                <a href="index.php?sec=admin_ordenes" class="btn btn-secondary">Volver a Órdenes</a>
            </div>
            
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Detalles de la Orden</h5>
                </div>
                <div class="card-body">
                    <form action="../actions/update_orden.php" method="POST">
                        <input type="hidden" name="orden_id" value="<?= htmlspecialchars($orden_id) ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Usuario:</strong> <?= htmlspecialchars($orden['username']) ?> (<?= htmlspecialchars($orden['nombre_completo']) ?>)</p>
                                <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($orden['fecha_orden']))) ?></p>
                                <p><strong>Importe Total:</strong> $<?= htmlspecialchars(number_format($orden['total'], 2, ',', '.')) ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado de la Orden</label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="Pendiente" <?= $orden['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="Completada" <?= $orden['estado'] == 'Completada' ? 'selected' : '' ?>>Completada</option>
                                        <option value="Cancelada" <?= $orden['estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5 class="mb-3">Productos en la Orden</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orden['productos'] as $producto): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                            <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                                            <td>$<?= htmlspecialchars(number_format($producto['precio_unitario'], 2, ',', '.')) ?></td>
                                            <td>$<?= htmlspecialchars(number_format($producto['cantidad'] * $producto['precio_unitario'], 2, ',', '.')) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar Orden</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>