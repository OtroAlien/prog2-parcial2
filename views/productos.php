<?php
require_once "functions/autoload.php";

$id = $_GET['id'] ?? FALSE;
$filtro = $_GET['filtro'] ?? FALSE;
$descuento = $_GET['descuento'] ?? FALSE;
$piel = $_GET['piel'] ?? FALSE;

$miProducto = new Producto();

if ($filtro) {
    $productos = $miProducto->catalogoPorCategoria($filtro);
} elseif ($descuento) {
    $productos = $miProducto->catalogoPorDescuento($descuento);
} elseif ($piel) {
    $productos = $miProducto->catalogoPorPiel($piel);
} else {
    $productos = $miProducto->catalogoCompleto();
}
?>

<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <p style="font-size: 1.25rem;" class="modal-title" id="authModalLabel">Inicia Sesión o Regístrate</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Debes iniciar sesión o registrarte para acceder a esta funcionalidad.</p>
        <a href="index.php?sec=login" class="btn btn-primary">Iniciar Sesión/Registrarse</a>
      </div>
    </div>
  </div>
</div>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/productos/productos-4.jpeg" class="d-block w-100" alt="Nuestros productos 1">
            <div class="carousel-caption d-none d-md-block">
                <div class="cc-carrusel">
                    <h2>Descubre Nuestra Colección</h2>
                    <p>Explora nuestra selección cuidadosamente curada de productos naturales, diseñados para nutrir tu piel, cabello y cuerpo de manera saludable y sostenible.</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/productos/productos-3.jpeg" class="d-block w-100" alt="Nuestros productos 2">
            <div class="carousel-caption d-none d-md-block">
                <div class="cc-carrusel">
                    <h2>Explora Nuestra Gama de Productos</h2>
                    <p>Productos creados con ingredientes orgánicos y libres de químicos agresivos, nuestros productos son una opción consciente para aquellos que buscan una belleza más saludable y respetuosa con el medio ambiente.</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/productos/productos-2.jpeg" class="d-block w-100" alt="Nuestros productos 3">
            <div class="carousel-caption d-none d-md-block">
                <div class="cc-carrusel">
                    <h2>Experimenta el Poder Transformador de la Naturaleza</h2>
                    <p>Déjate seducir por la belleza de la naturaleza con nuestra línea de productos naturales inspirados en los elementos más puros de la tierra.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<section class="sectionProducts">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="container mt-5">

                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="ingredientesHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ingredientesCollapse" aria-expanded="false" aria-controls="ingredientesCollapse"> Tipo de piel
                                </button>
                            </h2>
                            <div id="ingredientesCollapse" class="accordion-collapse collapse" aria-labelledby="ingredientesHeading" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <a href="index.php?sec=productos&piel=todoTipo" class="list-group-item">Todo tipo</a>
                                        <a href="index.php?sec=productos&piel=pielGrasa" class="list-group-item">Piel grasa</a>
                                        <a href="index.php?sec=productos&piel=pielMixta" class="list-group-item">Piel mixta</a>
                                        <a href="index.php?sec=productos&piel=pielSeca" class="list-group-item">Piel seca</a>
                                        <a href="index.php?sec=productos&piel=pielSeca" class="list-group-item">Piel madura</a>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="categoriaHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoriaCollapse" aria-expanded="false" aria-controls="categoriaCollapse">
                                    Categoría
                                </button>
                            </h2>
                            <div id="categoriaCollapse" class="accordion-collapse collapse" aria-labelledby="categoriaHeading" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                    <a href="index.php?sec=productos&filtro=Skincare" class="list-group-item">Skincare</a>
                                    <a href="index.php?sec=productos&filtro=Cabello" class="list-group-item">Hair</a>
                                    <a href="index.php?sec=productos&filtro=Maquillaje" class="list-group-item">Makeup</a>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="ofertasHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"  data-bs-target="#ofertasCollapse" aria-expanded="false" aria-controls="ofertasCollapse">
                                    Ofertas
                                </button>
                            </h2>
                            <div id="ofertasCollapse" class="accordion-collapse collapse" aria-labelledby="ofertasHeading" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <a href="index.php?sec=productos&descuento=15" class="list-group-item">Hasta 15%</a>
                                        <a href="index.php?sec=productos&descuento=20" class="list-group-item">Hasta 20%</a>
                                        <a href="index.php?sec=productos&descuento=40" class="list-group-item">Hasta 40%</a>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                        <h2 class="accordion-header" id="ofertasHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"  data-bs-target="#filtroCollapse" aria-expanded="false" aria-controls="filtroCollapse">
                                    Quitar Filtro
                                </button>
                            </h2>
                            <div id="filtroCollapse" class="accordion-collapse collapse" aria-labelledby="quitarFiltro" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <a href="index.php?sec=productos" class="list-group-item">Quitar Filtro</a>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-9">

            <!-- Buscador de productos -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="buscadorProductos" class="form-control" placeholder="Buscar productos por nombre, descripción o categoría...">
                    <select id="filtroRapido" class="form-select" style="max-width: 200px;">
                        <option value="">Filtrar por...</option>
                        <option value="categoria">Categoría</option>
                        <option value="descuento">Descuento</option>
                        <option value="piel">Tipo de piel</option>
                    </select>
                </div>
            </div>

            <!-- Filtros rápidos -->
            <div id="filtrosAdicionales" class="mb-4" style="display: none;">
                <!-- Los filtros se cargarán dinámicamente según la selección -->
            </div>
            <div class="container">
                <div class="row" id="productosContainer">
                <?php if (!empty($productos)) {
            foreach ($productos as $producto) { ?>
            <div class="col-md-6 col-lg-4 col-xl-4 col-sm-12">
                <div id="product-1" class="single-product" data-categoria="<?= $producto->getCategoria() ?>" data-descuento="<?= $producto->getDescuento() ?>" data-piel="<?= $producto->getPiel() ?>">
                    <div class="part-1">
                        <?php if ($producto->getDescuento() > 0) { ?>
                            <div class="descuento-cartelito">Descuento: <?= $producto->getDescuento() ?>%</div>
                        <?php } ?>
                        <div class="overlay"></div>
                        <img class="part-1 img" src="./img/<?= $producto->getImagen() ?>" alt="Imagen de <?= $producto->getNombre() ?>">
                        <ul>
                            <li>
                                <a href="admin/actions/add_to_cart.php?producto_id=<?= $producto->getId() ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                                        <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z" />
                                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                    </svg>
                                </a>
                            </li>
                            <li><a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.920 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.060.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                                    </svg>
                                </a></li>
                            <li><a href="index.php?sec=detalles&id=<?= $producto->getId() ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                    </svg>
                                </a></li>
                        </ul>
                    </div>
                    <div class="part-2">
                        <h3 class="product-title"><?= $producto->getNombre() ?></h3>
                        <p class="product-description"><?= $producto->getDescripcion() ?></p>
                        <div class="d-flex">
                            <p class="product-old-price me-2">
                                <del>$<?= $producto->precioFormateado() ?></del>
                            </p>
                            <p class="product-price fw-bold">$<?= $producto->precioDescuento() ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            } else { ?>
                <div class="col">
                    <h2 class="text-center m-5">No se encontraron productos.</h2>
                </div>
            <?php } ?>

                </div>
            </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de productos
        const buscadorProductos = document.getElementById('buscadorProductos');
        const filtroRapido = document.getElementById('filtroRapido');
        const filtrosAdicionales = document.getElementById('filtrosAdicionales');
        
        // Configuración de filtros adicionales
        const filtrosConfig = {
            categoria: [
                { valor: 'Skincare', nombre: 'Skincare' },
                { valor: 'Cabello', nombre: 'Hair' },
                { valor: 'Maquillaje', nombre: 'Makeup' }
            ],
            descuento: [
                { valor: '15', nombre: 'Hasta 15%' },
                { valor: '20', nombre: 'Hasta 20%' },
                { valor: '40', nombre: 'Hasta 40%' }
            ],
            piel: [
                { valor: 'todoTipo', nombre: 'Todo tipo' },
                { valor: 'pielGrasa', nombre: 'Piel grasa' },
                { valor: 'pielMixta', nombre: 'Piel mixta' },
                { valor: 'pielSeca', nombre: 'Piel seca' },
                { valor: 'pielMadura', nombre: 'Piel madura' }
            ]
        };
        
        // Evento para cambio de filtro rápido
        if (filtroRapido) {
            filtroRapido.addEventListener('change', function() {
                const tipoFiltro = this.value;
                
                if (tipoFiltro) {
                    // Mostrar filtros adicionales
                    filtrosAdicionales.style.display = 'block';
                    
                    // Generar opciones de filtro
                    let opcionesFiltro = '<div class="d-flex align-items-center">';
                    opcionesFiltro += '<span class="me-2">Seleccione ' + tipoFiltro + ':</span>';
                    opcionesFiltro += '<select id="filtroEspecifico" class="form-select" style="max-width: 200px;">';
                    opcionesFiltro += '<option value="">Todos</option>';
                    
                    filtrosConfig[tipoFiltro].forEach(opcion => {
                        opcionesFiltro += `<option value="${opcion.valor}">${opcion.nombre}</option>`;
                    });
                    
                    opcionesFiltro += '</select>';
                    opcionesFiltro += '</div>';
                    
                    filtrosAdicionales.innerHTML = opcionesFiltro;
                    
                    // Agregar evento al nuevo select
                    const filtroEspecifico = document.getElementById('filtroEspecifico');
                    if (filtroEspecifico) {
                        filtroEspecifico.addEventListener('change', function() {
                            aplicarFiltros();
                        });
                    }
                } else {
                    // Ocultar filtros adicionales
                    filtrosAdicionales.style.display = 'none';
                    filtrosAdicionales.innerHTML = '';
                    mostrarTodosProductos();
                }
            });
        }
        
        // Función para aplicar filtros
        function aplicarFiltros() {
            const tipoFiltro = filtroRapido.value;
            const valorFiltro = document.getElementById('filtroEspecifico')?.value || '';
            const textoBusqueda = buscadorProductos.value.toLowerCase();
            const productosItems = document.querySelectorAll('#productosContainer .col-md-6');
            
            productosItems.forEach(item => {
                const nombre = item.querySelector('.product-title').textContent.toLowerCase();
                const descripcion = item.querySelector('.product-description').textContent.toLowerCase();
                const productoElement = item.querySelector('.single-product');
                const categoria = productoElement.getAttribute('data-categoria') || '';
                const descuento = productoElement.getAttribute('data-descuento') || '';
                const piel = productoElement.getAttribute('data-piel') || '';
                
                // Verificar búsqueda por texto
                const cumpleBusqueda = textoBusqueda === '' || 
                    nombre.includes(textoBusqueda) || 
                    descripcion.includes(textoBusqueda) || 
                    categoria.toLowerCase().includes(textoBusqueda);
                
                // Verificar filtro específico
                let cumpleFiltro = true;
                
                if (valorFiltro) {
                    switch(tipoFiltro) {
                        case 'categoria':
                            cumpleFiltro = categoria === valorFiltro;
                            break;
                        case 'descuento':
                            cumpleFiltro = descuento == valorFiltro;
                            break;
                        case 'piel':
                            cumpleFiltro = piel === valorFiltro;
                            break;
                    }
                }
                
                // Mostrar u ocultar según los filtros
                if (cumpleBusqueda && cumpleFiltro) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        // Función para mostrar todos los productos
        function mostrarTodosProductos() {
            const productosItems = document.querySelectorAll('#productosContainer .col-md-6');
            productosItems.forEach(item => {
                item.style.display = '';
            });
        }
        
        // Evento para búsqueda por texto
        if (buscadorProductos) {
            buscadorProductos.addEventListener('keyup', function() {
                aplicarFiltros();
            });
        }
    });
</script>