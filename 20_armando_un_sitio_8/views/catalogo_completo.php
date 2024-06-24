<?PHP

$catalogo = (new Comic())->catalogo_completo();

?>
<div class=" d-flex justify-content-center p-5">
    <div>
        <h1 class="text-center mb-5 fw-bold">Nuesto catálogo de <span class="text-danger">completo</span>

        </h1>
        <div class="container">

            <?PHP if (!empty($catalogo)) { ?>
                <div class="row">

                    <?PHP foreach ($catalogo as $comic) { 
                        
                        ?>

                        <div class="col-12 col-md-4">
                            <div class="card mb-3">
                                <img src="img/covers/<?= $comic->getPortada()?>" class="card-img-top" alt="Portada de <?= $comic->nombre_completo() ?>">
                                <div class="card-body">
                                    <p class="fs-6 m-0 fw-bold text-danger"><?= $comic->nombre_completo() ?></p>
                                    <h5 class="card-title"><?= $comic->getTitulo() ?></h5>
                                    <p class="card-text"><?= $comic->bajada_reducida() ?></p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><span class="fw-bold">Guion:</span> <?= $comic->getGuion() ?></li>
                                    <li class="list-group-item"><span class="fw-bold">Arte:</span> <?= $comic->getArte() ?></li>
                                    <li class="list-group-item"><span class="fw-bold">Publicación:</span> <?= $comic->getPublicacion() ?></li>
                                </ul>
                                <div class="card-body">
                                    <div class="fs-3 mb-3 fw-bold text-center text-danger">$<?= $comic->precio_formateado() ?></div>
                                    <a href="index.php?sec=producto&id=<?= $comic->getId()?>" class="btn btn-danger w-100 fw-bold">VER MÁS</a>
                                </div>

                            </div>
                        </div>

                    <?PHP } ?>

                </div>

            <?PHP } else { ?>
                <div class="row">
                    <div class="col-12 text-danger text-center h1"> NO SE ENCONTRARON PRODUCTOS EN STOCK</div>
                </div>
            <?PHP }  ?>
        </div>

    </div>
</div>