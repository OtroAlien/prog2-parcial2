<?PHP
$items = (new Carrito())->get_carrito();
?>

<h2 class="text-center fs-2 my-5"> Carrito de Compras</h2>
<div class="container my-4">

    <?PHP if (count($items)) { ?>
        <form action="admin/actions/update_items_acc.php" method="POST">

            <table class="table">

                <thead>
                    <tr>
                        <th scope="col" width="15%">Portada</th>
                        <th scope="col">Datos del producto</th>
                        <th scope="col" width="15%">Cantidad</th>
                        <th class="text-end" scope=" col" width="15%">Precio Unitario</th>
                        <th class="text-end" scope="col" width="15%">Subtotal</th>
                        <th class="text-end" scope="col" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP foreach ($items as $key => $item) { ?>
                        <tr>
                            <td><img src="img/covers/<?= $item['portada'] ?>" alt="Imágen Illustrativa de <?= $item['titulo'] ?>" class="img-fluid rounded shadow-sm"></td>

                            <td class="align-middle">
                                <h3 class="h5"><?= $item['producto'] ?></h3>
                                <p><?= $item['titulo'] ?></p>
                            </td>
                            <td class="align-middle">
                                <label for="q_<?= $key ?>" class="visually-hidden">Cantidad</label>
                                <input type="number" class="form-control" value="<?= $item['cantidad'] ?>" id="q_<?= $key ?>" name="q[<?= $key ?>]">
                            </td>
                            <td class="text-end align-middle">
                                <p class="h5 py-3">$<?= number_format($item['precio'], 2, ",", ".") ?></p>
                            </td>
                            <td class="text-end align-middle">
                                <p class="h5 py-3"> $<?= number_format($item['cantidad'] * $item['precio'], 2, ",", ".") ?></p>
                            </td>
                            <td class="text-end align-middle">
                                <a href="admin/actions/remove_item_acc.php?id=<?= $key ?>" class="btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?PHP } ?>

                    <tr>
                        <td colspan="4" class="text-end">
                            <h3 class="h5 py-3">Total:</h3>
                        </td>
                        <td class="text-end">

                        <p class="h5 py-3">$<?= number_format((new Carrito())->precio_total(), 2, ",", ".") ?></p>
                        
                        </td>
                        <td></td>
                    </tr>
                </tbody>



            </table>



            <div class="d-flex justify-content-end gap-2">
                <input type="submit" value="Actualizar Cantidades" class="btn btn-warning">
                <a href="index.php?sec=catalogo_completo" role="button" class=" btn btn-danger">Seguir comprando</a>
                <a href="admin/actions/clear_items_acc.php" role="button" class="btn btn-danger">Vaciar Carrito</a>
                <a href="index.php?sec=finalizar_pago" role="button" class="btn btn-primary">Finalizar Compra</a>
            </div>

        </form>
    <?PHP } else { ?>
        <h2 class="text-center mb-5 text-danger">Su carrito esta vacio</h2>
    <?PHP } ?>


</div>