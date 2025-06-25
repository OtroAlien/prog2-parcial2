<?php
$items = (new Carrito())->get_carrito();
?>
<div class="container">

    <h2 class="text-center mb-5 fw-bold">Finalizar Pago</h2>

    <div class="border rounded p-3 mb-4">

        <div class="row">

            <div class="col-12">

                <section>
                    <h3>Datos de Usuario</h3>
                    <?php if (isset($_SESSION['loggedIn'])) { ?>
                        <div class="card p-3 mb-3">
                            <p><strong>Usuario:</strong> <?= htmlspecialchars($_SESSION['loggedIn']['username'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['loggedIn']['email'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['loggedIn']['nombre_completo'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    <?php } ?>
                </section>

                <section>
                    <h3>Resumen de Compra</h3>
                    <?php if (count($items)) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col" width="15%">Cantidad</th>
                                    <th class="text-end" scope="col" width="15%">Precio Unitario</th>
                                    <th class="text-end" scope="col" width="15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $key => $item) { ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="img/productos/<?= $item['imagen'] ?>" alt="<?= $item['nombre'] ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h5 class="mb-1"><?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?></h5>
                                                    <p class="mb-0 text-muted"><?= htmlspecialchars($item['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary"><?= $item['cantidad'] ?></span>
                                        </td>
                                        <td class="text-end align-middle">
                                            <span class="fw-bold">$<?= number_format($item['precio'], 2, ",", ".") ?></span>
                                        </td>
                                        <td class="text-end align-middle">
                                            <span class="fw-bold">$<?= number_format($item['cantidad'] * $item['precio'], 2, ",", ".") ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end">
                                        <h4 class="mb-0">Total:</h4>
                                    </td>
                                    <td class="text-end">
                                        <h4 class="mb-0 text-primary">$<?= number_format((new Carrito())->precio_total(), 2, ",", ".") ?></h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php?sec=carrito" class="btn btn-outline-secondary me-md-2">Volver al Carrito</a>
                            <a href="admin/actions/checkout.php" class="btn btn-success btn-lg">Confirmar Pago</a>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-warning text-center">
                            <h4>Tu carrito está vacío</h4>
                            <p>Agrega algunos productos antes de proceder al pago.</p>
                            <a href="index.php?sec=productos" class="btn btn-primary">Ver Productos</a>
                        </div>
                    <?php } ?>
                </section>

            </div>

        </div>

    </div>

</div>