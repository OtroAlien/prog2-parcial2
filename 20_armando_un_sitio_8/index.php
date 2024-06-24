<?PHP
require_once "functions/autoload.php";



$protagonistas = (new Personaje())->listar_protagonistas();

$secciones_validas = [
    "home" => [
        "titulo" => "Bienvenidos",
        "restringido" => FALSE
    ],
    "envios" => [
        "titulo" => "Políticas de envío",
        "restringido" => FALSE
    ],
    "quienes_somos" => [
        "titulo" => "¿Quienes Somos?",
        "restringido" => FALSE
    ],
    "comics" => [
        "titulo" => "Nuestro catálogo",
        "restringido" => FALSE
    ],
    "producto" => [
        "titulo" => "Detalle de Producto",
        "restringido" => FALSE
    ],
    "test" => [
        "titulo" => "Página de Testeo",
        "restringido" => FALSE
    ],
    "carrito" => [
        "titulo" => "Carrito de compras",
        "restringido" => FALSE
    ],
    "login" => [
        "titulo" => "Inicio de Sesión",
        "restringido" => FALSE
    ],
    "panel_usuario" => [
        "titulo" => "Panel de Usuario",
        "restringido" => TRUE
    ],
    "finalizar_pago" => [
        "titulo" => "Finalizar Pago",
        "restringido" => TRUE
    ]

];

$seccion = isset($_GET['sec']) ? $_GET['sec'] : 'home';

if (!array_key_exists($seccion, $secciones_validas)) {
    $vista = "404";
    $titulo = "404: Página no encontrada";
} else {
    $vista = $seccion;


    if ($secciones_validas[$seccion]['restringido']) {
        (new Autenticacion())->verify(FALSE);
    }


    $titulo = $secciones_validas[$seccion]['titulo'];
}

$userData = $_SESSION['loggedIn'] ?? FALSE;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Tiendita de Comics :: <?= $titulo ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link href="css/styles.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">La Tiendita de Comics</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?sec=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?sec=quienes_somos">¿Quienes Somos?</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold text-danger" href="index.php?sec=catalogo_completo">Catalogo Completo</a>
                    </li>

                    <?PHP foreach ($protagonistas as $pagina) { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?sec=comics&per=<?= $pagina['id'] ?>"><?= $pagina['alias'] ?></a>
                        </li>

                    <?PHP } ?>

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?sec=envios">Envios</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link bg-secondary text-light rounded me-2" href="admin">Admin</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link bg-danger text-light rounded me-2" href="index.php?sec=carrito">🛒 Carrito</a>
                    </li>



                    <li class="nav-item <?= $userData ? "d-none" : "" ?>">
                        <a class="nav-link fw-bold" href="index.php?sec=login">Login</a>
                    </li>

              

                    <?PHP if ($userData) { ?>
                        <li class="nav-item">
                            <a class="nav-link bg-danger text-light rounded me-2" href="index.php?sec=panel_usuario">🙍‍♂️​ <?= $userData['username'] ?? "" ?> </a>
                        </li>
                    <?PHP } ?>

                    <li class="nav-item <?= $userData ? "" : "d-none" ?>">
                        <a class="nav-link fw-bold" href="actions/auth_logout.php">Logout <span class="fw-light"></span></a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        <?PHP

        require_once "views/$vista.php";

        ?>
    </main>

    <footer class="bg-secondary text-light text-center">
        Jorge Perez 2023
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>