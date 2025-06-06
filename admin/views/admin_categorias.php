<?php
require_once "../functions/autoload.php";

$categorias = Categoria::obtenerTodas();

// Obtener el ID de la categoría seleccionada si existe
$categoria_id = $_GET['categoria_id'] ?? null;
$categoria_seleccionada = null;

if ($categoria_id) {
    $categoria_seleccionada = Categoria::categoriaPorId($categoria_id);
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Panel lateral para edición de categorías -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Gestión de Categorías</h5>
                </div>
                <div class="card-body">
                    <form action="../actions/<?= $categoria_seleccionada ? 'update_category.php?id='.$categoria_seleccionada->getId() : 'add_category.php' ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $categoria_seleccionada ? htmlspecialchars($categoria_seleccionada->getNombre()) : '' ?>" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><?= $categoria_seleccionada ? 'Actualizar Categoría' : 'Agregar Categoría' ?></button>
                            <?php if ($categoria_seleccionada): ?>
                                <a href="index.php?sec=admin_productos" class="btn btn-secondary">Cancelar Edición</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tabla de categorías -->
        <div class="col-md-8">
            <h2 class="text-center mb-5 fw-bold">Administración de Categorías</h2>
            <div class="d-flex justify-content-between mb-3">
                <a href="../admin/index.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria) { ?>
                            <tr>
                                <td><?= htmlspecialchars($categoria->getId()) ?></td>
                                <td><?= htmlspecialchars($categoria->getNombre()) ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <a href="index.php?sec=admin_productos&categoria_id=<?= htmlspecialchars($categoria->getId()) ?>" role="button" class="btn btn-success btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                        </a>
                                        <a href="index.php?sec=delete_category&id=<?= htmlspecialchars($categoria->getId()) ?>" role="button" class="btn btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>