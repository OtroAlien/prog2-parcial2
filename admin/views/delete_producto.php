<?PHP
$id = $_GET['id'] ?? FALSE;
$producto = (new Producto())->producto_x_id($id);
?>

<div class="row my-5 g-3">
	<h1 class="text-center mb-5 fw-bold">¿Está seguro que desea eliminar este comic?</h1>
	<div class="col-12 col-md-6">
		<img src="../img/covers/<?= $comic->getPortada() ?>" alt="Imágen Illustrativa de <?= $comic->getNombre() ?>" class="img-fluid rounded shadow-sm d-block mx-auto mb-3">
	</div>

	<div class="col-12 col-md-6">


		<h2 class="fs-6">Nombre</h2>
		<p><?= $comic->getNombre() ?></p>


		<a href="actions/delete_comic_acc.php?id=<?= $comic->getId() ?>" role="button" class="d-block btn btn-sm btn-danger mt-4">Eliminar</a>
	</div>



</div>