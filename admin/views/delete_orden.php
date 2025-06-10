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
            <h2 class="text-center mb-4 fw-bold">Eliminar Orden #<?= htmlspecialchars($orden_id) ?></h2>
            
            <div class="d-flex justify-content-between mb-4">
                <a href="index.php?sec=admin_ordenes" class="btn btn-secondary">Volver a Órdenes</a>
            </div>
            
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Confirmar Eliminación</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <p><strong>¡Atención!</strong> Está a punto de eliminar la orden #<?= htmlspecialchars($orden_id) ?>. Esta acción no se puede deshacer.</p>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Usuario:</strong> <?= htmlspecialchars($orden['username']) ?> (<?= htmlspecialchars($orden['nombre_completo']) ?>)</p>
                            <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($orden['fecha_orden']))) ?></p>
                            <p><strong>Importe Total:</strong> $<?= htmlspecialchars(number_format($orden['total'], 2, ',', '.')) ?></p>
                            <p><strong>Estado:</strong> <span class="badge bg-<?= $orden['estado'] == 'Completada' ? 'success' : ($orden['estado'] == 'Pendiente' ? 'warning' : 'danger') ?>"><?= htmlspecialchars($orden['estado']) ?></span></p>
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
                    
                    <form action="../actions/delete_orden.php" method="POST" class="mt-4">
                        <input type="hidden" name="orden_id" value="<?= htmlspecialchars($orden_id) ?>">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?sec=admin_ordenes" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>