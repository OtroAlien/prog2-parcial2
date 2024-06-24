<?PHP
$id_personaje = $_GET['per'] ?? FALSE;

/*LOS DATOS DE ESTE PERSONAJE*/
$personaje = (new Personaje())->get_x_id($id_personaje);

$titulo = $personaje->getTitulo(TRUE);
$biografia = $personaje->getBiografia();
$creador = $personaje->getCreador();

/*EL CATÁLOGO DE ESTE PERSONAJE*/
$catalogo = (new Comic())->catalogo_x_personaje($id_personaje);



?>
<div class=" d-flex justify-content-center p-5">
    <div class="container">

        <h1 class="text-center mb-5 fw-bold">Nuestro Catálogo de <span class="text-danger"><?= $titulo ?></span></h1>

        <div class="border rounded p-3 mb-4">
            <h2>Bio:</h2>
            <span class="fw-bold">Creado/a por: </span><span><?= $creador ?></span>
            <p><?= $biografia ?></p>
        </div>

        <div class="row">

            <?PHP foreach ($catalogo as $comic) { ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card mb-3">
                        <img src="img/covers/<?= $comic->getPortada() ?>" class="card-img-top" alt="Portada de <?= $comic->nombre_completo() ?>">
                        <div class="card-body">
                            <p class="fs-6 m-0 fw-bold text-danger"><?= $comic->nombre_completo() ?></p>
                            <h5 class="card-title"><?= $comic->getTitulo() ?></h5>
                            <p class="card-text"><?= $comic->bajada_reducida(20) ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span class="fw-bold">Guion:</span> <?= $comic->getGuion() ?></li>
                            <li class="list-group-item"><span class="fw-bold">Arte:</span> <?= $comic->getArte() ?></li>
                            <li class="list-group-item"><span class="fw-bold">Publicación:</span> <?= $comic->getPublicacion() ?></li>
                        </ul>
                        <div class="card-body">
                            <div class="fs-3 mb-3 fw-bold text-center text-danger">$<?= $comic->precio_formateado() ?></div>
                            <a href="index.php?sec=producto&id=<?= $comic->getId() ?>" class="btn btn-danger w-100 fw-bold">VER MAS</a>
                        </div>

                    </div>
                </div>
            <?PHP } ?>

        </div>

    </div>

</div>