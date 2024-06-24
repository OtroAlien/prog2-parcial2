<?PHP
$id = $_GET['id'] ?? FALSE;

$comic = (new Comic())->producto_x_id($id);

?>
<div class="container">
    <div class="row">

        <?PHP if (!empty($comic)) { ?>
            <h1 class="text-center my-5"> <?= $comic->nombre_completo() ?></h1>
            <div class="col">
                <div class="card mb-5">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <img src="img/covers/<?= $comic->getPortada() ?>" class="img-fluid rounded-start border-end" alt="Portada de  <?= $comic->nombre_completo() ?>">
                        </div>
                        <div class="col-md-7 d-flex flex-column p-3">
                            <div class="card-body flex-grow-0">
                                <p class="fs-4 m-0 fw-bold text-danger"><?= $comic->nombre_completo() ?></p>
                                <h2 class="card-title fs-2 mb-4"><?= $comic->getTitulo(); ?></h2>
                                <p class="card-text"><?= $comic->getBajada() ?></p>
                            </div>

                            <ul class="list-group list-group-flush border-top border-bottom">

                                <li class="list-group-item">
                                    <h3 class="fs-5">Serie: <span class="text-danger"><?= $comic->getSerie()->getNombre(); ?></span></h3>
                                    <p class="text-dark fst-italic"><?= $comic->getSerie()->getHistoria(); ?></p>


                                </li>


                                <li class="list-group-item">
                                    <h3 class="mb-3">Personaje Principal</h3>
                                    <div class="row">
                                        <div class="col-4"><img src="img/personajes/<?= $comic->getPersonaje_principal()->getImagen() ?>" alt="Imágen Illustrativa de <?= $comic->getPersonaje_principal()->getTitulo() ?>" class="img-fluid rounded shadow-sm"></div>
                                        <div class="col-8">
                                            <h3 class="fs-5 text-danger"><?= $comic->getPersonaje_principal()->getTitulo(); ?></h3>
                                            <p class="text-dark fst-italic"><?= $comic->getPersonaje_principal()->getBiografia(); ?></p>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <h3 class="mb-3">Personajes Secundarios</h3>
                                    <?PHP foreach ($comic->getPersonajes_secundarios() as $value) { ?>
                                        <div class="row pb-3 mb-3 border-bottom">
                                            <div class="col-9">
                                                <h3 class="fs-5 text-danger"><?= $value->getTitulo(); ?></h3>
                                                <p class="text-dark fst-italic"><?= $value->getBiografia(); ?></p>
                                            </div>
                                            <div class="col-3"><img src="img/personajes/<?= $value->getImagen() ?>" alt="Imágen Illustrativa de <?= $value->getTitulo() ?>" class="img-fluid rounded shadow-sm"></div>

                                        </div>
                                    <?PHP  } ?>

                                </li>



                                <li class="list-group-item">
                                    <h3 class="fs-5">Guion: <span class="text-danger"><?= $comic->getGuionista()->getNombre_completo(); ?></span></h3>
                                    <p class="text-dark fst-italic"><?= $comic->getGuionista()->getBiografia(); ?></p>
                                </li>

                                <li class="list-group-item">
                                    <h3 class="fs-5">Arte: <span class="text-danger"><?= $comic->getArtista()->getNombre_completo(); ?></span></h3>
                                    <p class="text-dark fst-italic"><?= $comic->getArtista()->getBiografia(); ?></p>
                                </li>

                                <li class="list-group-item"><span class="fw-bold">Publicación:</span> <?= $comic->getPublicacion() ?></li>
                            </ul>

                            <div class="card-body flex-grow-0 mt-auto">
                                <div class="fs-3 mb-3 fw-bold text-center text-danger">$<?= $comic->precio_formateado() ?></div>

                                <form action="admin/actions/add_item_acc.php" method="GET" class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <label for="q" class="fw-bold me-2">Cantidad: </label>
                                        <input type="number" class="form-control" value="1" name="q" id="q">
                                    </div>
                                    <div class="col-6">
                                        <input type="submit" value="AGREGAR A CARRITO" class="btn btn-danger w-100 fw-bold">
                                        <input type="hidden" value="<?= $id ?>" name="id" id="id">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>




        <?PHP } else { ?>
            <div class="col">
                <h2 class="text-center m-5">No se encontró el producto deseado.</h2>
            </div>
        <?PHP } ?>



    </div>

</div>