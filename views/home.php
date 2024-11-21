<?php
require_once "functions/autoload.php";

$miProducto = new Producto();

$productosDestacados = $miProducto->catalogoDestacado(true);
?>

<div class="banner">
        <img class="background" src="img/banner/banner1.jpg" alt="imagen banner 1">
        <div class="info">
            <h2>Diseñado para realzar tu belleza</h2>
            <p class="pExplore">En NATURE, creemos en realzar tu belleza natural. Nuestra gama de productos de belleza de alta calidad está diseñada para hacerte sentir segura y radiante en cada momento. Únete a nosotros y descubre tu mejor versión.</p>
            <p>¡Descubre nuestros productos más populares y encuentra tu mejor versión!</p>
        </div>
    </div>

    <main class="Contenido">
        <section class="categories">
            <h2>Categorias</h2>
            <div class="catGrid">
                <div class="card1 text-bg-dark">
                    <a href="index.php?sec=productos&filtro=Skincare">
                        <img src="img/img-cuadrada-1.jpg" class="card-img" alt="imagen de productos para skincare">
                        <div class="card-img-overlay">
                            <h3 class="card-title">Skincare</h3>
                        </div>
                    </a>
                </div>
                <div class="card1 text-bg-dark">
                    <a href="index.php?sec=productos&filtro=Maquillaje">
                        <img src="img/img-cuadrada-2.jpg" class="card-img" alt="imagen de productos para maquillarse">
                        <div class="card-img-overlay">
                            <h3 class="card-title">Makeup</h3>
                        </div>
                    </a>
                </div>
                <div class="card1 text-bg-dark">
                    <a href="index.php?sec=productos&filtro=Cabello">
                        <img src="img/img-cuadrada-3.jpg" class="card-img" alt="imagen de productos para el cabello">
                        <div class="card-img-overlay">
                            <h3 class="card-title">Hair</h3>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    
        <section id="about" class="about">
            <div class="infoAbout">
                <h2>Diseñado para realzar tu belleza</h2>
                <p class="pExplore">En NATURE, creemos en realzar tu belleza natural. Nuestra gama de productos de belleza de alta calidad está diseñada para hacerte sentir segura y radiante en cada momento. Únete a nosotros y descubre tu mejor versión.</p>
                <p>Conoce más sobre nuestra filosofía y compromiso con la belleza natural.</p>
            </div>
            <div class="imagen">
                <img src="img/imagen2.jpg" alt="imagen promocional para informacion">
            </div>
        </section>

        <section class="section3">
            <div class="infoSec3">
                <h2>Siempre cuidamos lo más importante para vos, tu seguridad, belleza y salud.</h2>
                <p>Por eso, nos enorgullece decir que la composición de nuestros productos es completamente segura y suave para todo tipo de piel.</p>
            </div>
            <div class="section3img">
                <img src="img/imagen1.jpg" alt="imagen promocional para informacion">
            </div>
            <div class="frase">
                <p>Priorizamos un enfoque personalizado para el cuidado de la piel, ofreciendo productos eficaces adaptados a sus necesidades únicas. Valoramos profundamente a nuestros clientes, los mantenemos informados sobre nuevos productos y los deleitamos con obsequios ocasionales.</p>
            </div>
        </section>

        <section class="sectionProducts">
            <div class="ofertas">
                <div class="container-home">
                    <h2>Nustros productos destacados de la semana</h2>
                    <div class="row">

                <?php if (!empty($productosDestacados)) {
                $productoDestacado = array_slice($productosDestacados, 0, 3);

                foreach ($productoDestacado as $producto) {  ?>
                            <div class="col-md-6 col-lg-4 col-xl-4 col-sm-12">
                            <div id="product-1" class="single-product">
                                <div class="part-1">
                                    <?php if ($producto->getDescuento() > 0) { ?>
                                        <div class="descuento-cartelito">Descuento: <?= $producto->getDescuento() ?>%</div>
                                    <?php } ?>
                                    <div class="overlay"></div>
                                    <img class="part-1 img" src="./img/<?= $producto->getImagen() ?>" alt="Imagen de <?= $producto->getNombre() ?>">
                                    <ul>
                                        <li><a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                                                    <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z" />
                                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                </svg>
                                            </a></li>
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
                                    <h4 class="product-old-price">$<?= $producto->precioFormateado() ?></h4>
                                    <h4 class="product-price">$<?= $producto->precioDescuento() ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div class="col">
                        <h2 class="text-center m-5">No se encontraron productos destacados.</h2>
                    </div>
                    
                <?php } ?>
            </div>
        </section>

        <section class="guia">  
            <div class="guide">
                <div>
                    <img class="imgGuide" src="img/img-cuadrada-1.jpg" alt="imagen promocional para informacion">
                </div>
                <div class="textGuide">
                    <h2>Guía de Prueba de Parches</h2>
                    <p>Las pruebas de parche son una herramienta sencilla para prever reacciones negativas iniciales a un producto nuevo. Se recomienda realizar una prueba de parche antes de usar un producto por primera vez en tu rutina.</p>
                    <p>Aprende cómo realizar una prueba de parche correctamente y protege tu piel.</p>
                </div>
            </div>
        </section>
    </main>