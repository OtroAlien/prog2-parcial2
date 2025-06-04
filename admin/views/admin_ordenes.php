<?php
require_once "../functions/autoload.php";

// Obtener todas las órdenes
$ordenes = [];
try {
    $ordenes = (new Compra())->obtenerTodasLasOrdenes();
} catch (Exception $e) {
    echo "<p class='text-center text-danger'>Error al cargar las órdenes: " . $e->getMessage() . "</p>";
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-5 fw-bold">Administración de Órdenes</h2>
            
            <div class="d-flex justify-content-between mb-3">
                <a href="../admin/index.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>

            <?php if (empty($ordenes)): ?>
                <div class="alert alert-info">
                    <p class="text-center">No hay órdenes registradas en el sistema.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td><?= htmlspecialchars($orden['orden_id']) ?></td>
                                    <td><?= htmlspecialchars($orden['username']) ?> (<?= htmlspecialchars($orden['nombre_completo']) ?>)</td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($orden['fecha_orden']))) ?></td>
                                    <td>$<?= htmlspecialchars(number_format($orden['total'], 2, ',', '.')) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalleModal<?= $orden['orden_id'] ?>">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $orden['estado'] == 'Completada' ? 'success' : ($orden['estado'] == 'Pendiente' ? 'warning' : 'danger') ?>">
                                            <?= htmlspecialchars($orden['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="index.php?sec=edit_orden&id=<?= htmlspecialchars($orden['orden_id']) ?>" role="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal para ver detalles -->
                                <div class="modal fade" id="detalleModal<?= $orden['orden_id'] ?>" tabindex="-1" aria-labelledby="detalleModalLabel<?= $orden['orden_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detalleModalLabel<?= $orden['orden_id'] ?>">Detalle de Orden #<?= $orden['orden_id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Usuario:</strong> <?= htmlspecialchars($orden['username']) ?> (<?= htmlspecialchars($orden['nombre_completo']) ?>)</p>
                                                <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($orden['fecha_orden']))) ?></p>
                                                <p><strong>Importe Total:</strong> $<?= htmlspecialchars(number_format($orden['total'], 2, ',', '.')) ?></p>
                                                <p><strong>Estado:</strong> <?= htmlspecialchars($orden['estado']) ?></p>
                                                <hr>
                                                <h6>Productos:</h6>
                                                <ul>
                                                    <?php foreach (explode(', ', $orden['detalle']) as $item): ?>
                                                        <li><?= htmlspecialchars($item) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>