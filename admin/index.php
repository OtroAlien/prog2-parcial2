<?php

require_once "../functions/autoload.php";


$secciones_validas = [
    "login" => [
        "titulo" => "Inicio de Sesión",
        "restringido" => FALSE
    ],
    "dashboard" => [
        "titulo" => "Panel de administración",
        "restringido" => TRUE
    ],
    "admin_productos" => [
        "titulo" => "Administrar productos",
        "restringido" => TRUE
    ],
    "admin_usuarios" => [
        "titulo" => "Administrar Usuarios",
        "restringido" => TRUE
    ],
    "admin_categorias" => [
        "titulo" => "Administrar Categorías",
        "restringido" => TRUE
    ],
    "admin_descuentos" => [
        "titulo" => "Administrar Descuentos",
        "restringido" => TRUE
    ],
    "admin_subcategorias" => [
        "titulo" => "Administrar Subcategorías",
        "restringido" => TRUE
    ],
    "add_producto" => [
        "titulo" => "Agregar producto",
        "restringido" => TRUE
    ],
    "edit_producto" => [
        "titulo" => "Editar datos de producto",
        "restringido" => TRUE
    ],
    "delete_producto" => [
        "titulo" => "Eliminar datos de Producto",
        "restringido" => TRUE
    ],
    "duplicate_producto" => [
        "titulo" => "Duplicar Producto",
        "restringido" => TRUE
    ],
    "contacto" => [
        "titulo" => "Contacto",
        "restringido" => FALSE
    ],
    "delete_category" => [
        "titulo" => "Eliminar categorías",
        "restringido" => TRUE
    ],
    "delete_discount" => [
        "titulo" => "Eliminar descuentos",
        "restringido" => TRUE
    ],
    "delete_subcategoria" => [
        "titulo" => "Eliminar subcategorías",
        "restringido" => TRUE
    ],
    "edit_orden" => [
        "titulo" => "Editar Orden",
        "restringido" => TRUE
    ],
    "delete_orden" => [
        "titulo" => "Eliminar Orden",
        "restringido" => TRUE
    ],
    "admin_ordenes" => [
        "titulo" => "Administración de Órdenes",
        "restringido" => TRUE
    ],
];

$seccion = $_GET['sec'] ?? "dashboard";

if (!array_key_exists($seccion, $secciones_validas)) {
    $vista = "404";
    $titulo = "404 - Página no encontrada";
    $body_class = 'body-404';
} else {
    $vista = $seccion;

    if($secciones_validas[$seccion]['restringido']){
        (new Autenticacion())->verify();    
    }

    $titulo = $secciones_validas[$seccion]['titulo'];
    $body_class = "body-$seccion";
}

$userData = $_SESSION['loggedIn'] ?? FALSE;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NATURE | <?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/theme.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="<?= $body_class ?>">

    <main class="main">
        <section class="main">
            <?php include_once 'views/admin_navbar.php'; ?>
        </section>
    </main>
    <section>
    <?php
    if (in_array($vista, ['login', 'dashboard', 'admin_productos', 'admin_usuarios', 'admin_categorias', 'add_producto', 'edit_producto', 'delete_producto', 'duplicate_producto', 'delete_category','delete_subcategoria', 'admin_ordenes', 'edit_orden', 'delete_orden', 'admin_subcategorias', 'admin_descuentos', 'delete_discount'])) {
        include_once "views/$vista.php";
    } else {
        include_once "../views/$vista.php";
    }
    ?>
</section>

    <section>
        <?php include_once '../views/footer.php'; ?>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botones Leer más/menos
            const readMoreBtns = document.querySelectorAll('.read-more-btn');
            
            readMoreBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const hiddenText = this.parentNode.previousElementSibling;
                    const iconPlus = this.querySelector('.fa-plus');
                    const iconMinus = this.querySelector('.fa-minus');
                    if (hiddenText.style.display === 'none') {
                        hiddenText.style.display = 'block';
                        iconPlus.style.display = 'none';
                        iconMinus.style.display = 'inline-block';
                        this.querySelector('.button-text').textContent = 'Leer menos';
                    } else {
                        hiddenText.style.display = 'none';
                        iconPlus.style.display = 'inline-block';
                        iconMinus.style.display = 'none';
                        this.querySelector('.button-text').textContent = 'Leer más';
                    }
                });
            });
            
            // Funcionalidad de cambio de tema
            const themeToggle = document.getElementById('theme-toggle');
            
            // Verificar si hay una preferencia guardada
            const currentTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
            
            themeToggle.addEventListener('click', function() {
                // Si el tema actual es light, cambiar a dark y viceversa
                const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                
                // Actualizar el atributo en el HTML
                document.documentElement.setAttribute('data-theme', theme);
                
                // Guardar la preferencia en localStorage
                localStorage.setItem('theme', theme);
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.quantity-increase').on('click', function() {
            var $input = $(this).parent().find('input[name="cantidad"]');
            var newValue = parseInt($input.val()) + 1;
            $input.val(newValue);
        });

        $('.quantity-decrease').on('click', function() {
            var $input = $(this).parent().find('input[name="cantidad"]');
            var newValue = parseInt($input.val()) - 1;
            if (newValue >= 1) {
                $input.val(newValue);
            }
        });
    });
</script>


</body>
</html>
