<?PHP
$userID = $_SESSION['loggedIn']['id'] ?? FALSE;
?>




<div class="container">

    <h1 class="text-center mb-5 fw-bold">Panel de Usuario</h1>

    <div class="border rounded p-3 mb-4">

        <div>
            <?= (new Alerta())->get_alertas(); ?>
        </div>

        <div class="row">


            <div class="col-12 ">

                <h2 class="text-center mb-5 fw-bold">Historial de Compras</h2>



                <?PHP
                $compras = (new Compra())->compras_x_id_usuario($userID);

                // echo "<pre>";
                // print_r($compras);
                // echo "</pre>";
                ?>

                <table class="table">

                    <thead>
                        <tr>
                            <th scope="col" width="10%">ID</th>
                            <th scope="col" width="20%">Fecha</th>
                            <th class="" scope=" col">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?PHP foreach ($compras as  $C) { ?>
                            <tr>
                                <td class="align-middle">
                                    <p class="h5"><?= $C['id'] ?></p>
                                </td>
                                <td class="align-middle">
                                    <p class="h5"><?= $C['fecha'] ?></p>
                                </td>
                                <td class="align-middle">
                                    <p><?= $C['detalle'] ?></p>
                                </td>


                            </tr>
                        <?PHP } ?>


                    </tbody>



                </table>

            </div>


        </div>

    </div>
</div>