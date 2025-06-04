<?php

$productos = (new Producto())->catalogoCompleto();
$categorias = Categoria::obtenerTodas();
$descuentos = Producto::obtenerTodosDescuentos();

if (!$productos) {
    echo "<p>No se encontraron productos.</p>";
    return;
}

$producto_id = $_GET['producto_id'] ?? null;
$producto_seleccionado = null;

if ($producto_id) {
    $producto_seleccionado = (new Producto())->productoPorId($producto_id);
}
?>

<div class="container-admp">
<a href="../admin/index.php" class="btn btn-outline-success"><i class="fas fa-arrow-left me-2"></i>Atrás</a>
    <div class="row">
    <h2 class="text-center mb-3 fw-bold">Panel de Administración</h2>
        <div class="col-md-3 mb-4">
            
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 text-center">Administración General</h5>
                </div>
                <div class="card-body">
                    
                    <hr class="my-4">
                    <h6 class="mb-3">Agregar Nueva Categoría</h6>
                    <form action="actions/add_category.php" method="POST" class="mb-3">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" placeholder="Nombre de categoría" name="nombre" required>
                            <button class="btn btn-outline-success" type="submit">Agregar</button>
                        </div>
                    </form>

                    <!-- Lista de categorías existentes -->
                    <h6 class="mb-2">Categorías Existentes</h6>
                    <ul class="list-group list-group-flush small">
                        <?php foreach ($categorias as $categoria): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($categoria->getNombre()) ?>
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a href="#" class="text-primary edit-category" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="<?= htmlspecialchars($categoria->getId()) ?>" data-nombre="<?= htmlspecialchars($categoria->getNombre()) ?>" title="Editar categoría">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a href="index.php?sec=delete_category&id=<?= htmlspecialchars($categoria->getId()) ?>" class="text-danger" title="Eliminar categoría">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-body">
                    
                    <hr class="my-4">
                    <h6 class="mb-3">Descuentos</h6>
                    <form action="actions/add_category.php" method="POST" class="mb-3">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" placeholder="Nombre de categoría" name="nombre" required>
                            <button class="btn btn-outline-success" type="submit">Agregar</button>
                        </div>
                    </form>

                    <!-- Lista de descuentos existentes -->
                    <h6 class="mb-2">Descuentos Existentes</h6>
                    <ul class="list-group list-group-flush small">
                        <?php foreach ($descuentos as $descuento): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($descuento->nombre) ?>
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a href="#" class="text-primary edit-discount" data-bs-toggle="modal" data-bs-target="#editDiscountModal" data-valor="<?= htmlspecialchars($descuento->valor) ?>" title="Editar descuento">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a href="actions/delete_discount.php?valor=<?= htmlspecialchars($descuento->valor) ?>" class="text-danger" title="Eliminar descuento">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>   
                </div>
                <div class="card-body">
                    
                    <hr class="my-4">
                    <h6 class="mb-3">Contenidos</h6>
                    <form action="actions/add_category.php" method="POST" class="mb-3">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" placeholder="Contenido en ml" name="contenido" required>
                            <button class="btn btn-outline-success" type="submit">Agregar</button>
                        </div>
                    </form>

                    <!-- Lista de contenidos existentes -->
                    <h6 class="mb-2">Contenidos Existentes</h6>
                    <ul class="list-group list-group-flush small">
                        <?php $contenidos = Producto::obtenerTodosContenidos(); ?>
                        <?php foreach ($contenidos as $contenido): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($contenido->nombre) ?>
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a href="#" class="text-primary edit-contenido" data-bs-toggle="modal" data-bs-target="#editContenidoModal" data-valor="<?= htmlspecialchars($contenido->valor) ?>" title="Editar contenido">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a href="actions/delete_contenido.php?valor=<?= htmlspecialchars($contenido->valor) ?>" class="text-danger" title="Eliminar contenido">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>   
                </div>
            </div>
        </div>
        
        <!-- Tabla de productos -->
        <div class="col-md-9">
            
            <div class="d-flex justify-content-between mb-3">
                
                
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="buscadorProductos" class="form-control" placeholder="Buscar productos por nombre, descripción o categoría...">
                
            </div>
                <a href="index.php?sec=add_producto" class="btn btn-outline-success">Nuevo Producto <i class="fas fa-plus"></i></a>
            </div>

            <!-- Buscador de productos -->
            

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" width="10%">Imagen</th>
                            <th scope="col" width="10%" class="sortable" data-column="nombre">Nombre <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="descripcion">Descripción <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="precio">Precio <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="stock">Stock <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="categoria">Categoría <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="descuento">Descuento <span class="sort-icon"></span></th>
                            <th scope="col" class="sortable" data-column="waterproof">Waterproof <span class="sort-icon"></span></th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $P) { ?>
                            <tr>
                                <td>
                                    <div class="img-container">
                                        <img src="../img/<?= htmlspecialchars($P->getImagen()) ?>" alt="Imagen de <?= htmlspecialchars($P->getNombre()) ?>" class="img-thumbnail rounded">
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($P->getNombre()) ?></td>
                                <td><?= htmlspecialchars(substr($P->getDescripcion(), 0, 30)) . (strlen($P->getDescripcion()) > 30 ? '...' : '') ?></td>
                                <td>$<?= htmlspecialchars($P->getPrecio()) ?></td>
                                <td><?= htmlspecialchars($P->getStock()) ?></td>
                                <td><?= htmlspecialchars($P->getCategoria()) ?></td>
                                <td><?= htmlspecialchars($P->getDescuento()) ?>%</td>
                                <td><?= $P->getWaterproof() ? 'Sí' : 'No' ?></td>
                                <td>
                                    <a href="index.php?sec=edit_producto&id=<?= htmlspecialchars($P->getId()) ?>" class="text-primary" title="Editar producto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a href="index.php?sec=duplicate_producto&id=<?= htmlspecialchars($P->getId()) ?>" class="text-success" title="Duplicar producto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-files" viewBox="0 0 16 16">
                                            <path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/>
                                        </svg>
                                    </a>
                                    <a href="index.php?sec=delete_producto&id=<?= htmlspecialchars($P->getId()) ?>" class="text-danger" title="Eliminar producto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal para editar descuento -->
<div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDiscountModalLabel">Editar Descuento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDiscountForm" action="actions/update_discount.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="discountValue" class="form-label">Valor del Descuento (%)</label>
                        <input type="number" class="form-control" id="discountValue" name="valor" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar contenido -->
<div class="modal fade" id="editContenidoModal" tabindex="-1" aria-labelledby="editContenidoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editContenidoModalLabel">Editar Contenido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editContenidoForm" action="actions/update_contenido.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="contenidoValue" class="form-label">Valor del Contenido (ml)</label>
                        <input type="number" class="form-control" id="contenidoValue" name="valor" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar categoría -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" action="actions/update_category.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="categoryName" name="nombre" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para manejar la edición de descuentos, contenidos y categorías
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar click en editar descuento
        const editDiscountLinks = document.querySelectorAll('.edit-discount');
        editDiscountLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const valor = this.getAttribute('data-valor');
                document.getElementById('discountValue').value = valor;
                document.getElementById('editDiscountForm').action = `actions/update_discount.php?valor=${valor}`;
            });
        });
        
        // Manejar click en editar contenido
        const editContenidoLinks = document.querySelectorAll('.edit-contenido');
        editContenidoLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const valor = this.getAttribute('data-valor');
                document.getElementById('contenidoValue').value = valor;
                document.getElementById('editContenidoForm').action = `actions/update_contenido.php?valor=${valor}`;
            });
        });
        
        // Manejar click en editar categoría
        const editCategoryLinks = document.querySelectorAll('.edit-category');
        editCategoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                document.getElementById('categoryName').value = nombre;
                document.getElementById('editCategoryForm').action = `actions/update_category.php?id=${id}`;
            });
        });

        // Funcionalidad de búsqueda de productos
        const buscadorProductos = document.getElementById('buscadorProductos');
        if (buscadorProductos) {
            buscadorProductos.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasProductos = document.querySelectorAll('tbody tr');
                
                filasProductos.forEach(fila => {
                    const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const descripcion = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const categoria = fila.querySelector('td:nth-child(6)').textContent.toLowerCase();
                    
                    if (nombre.includes(textoBusqueda) || 
                        descripcion.includes(textoBusqueda) || 
                        categoria.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

</div>
</div>
