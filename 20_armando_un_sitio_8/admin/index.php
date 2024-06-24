<?PHP
require_once "../functions/autoload.php";

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

$secciones_validas = [
    "login" => [
        "titulo" => "Inicio de Sesión",
        "restringido" => FALSE
    ],
    "dashboard" => [
        "titulo" => "Panel de administración",
        "restringido" => TRUE
    ],
    "admin_comics" => [
        "titulo" => "Administrar comics",
        "restringido" => TRUE
    ],
    "admin_personajes" => [
        "titulo" => "Administrar Personajes",
        "restringido" => TRUE
    ],
    "admin_series" => [
        "titulo" => "Administrar series",
        "restringido" => TRUE
    ],
    "admin_artistas" => [
        "titulo" => "Administrar artistas",
        "restringido" => TRUE
    ],
    "admin_guionistas" => [
        "titulo" => "Administrar guionistas",
        "restringido" => TRUE
    ],
    "add_comic" => [
        "titulo" => "Agregar Personajes",
        "restringido" => TRUE
    ],
    "add_personaje" => [
        "titulo" => "Agregar Personajes",
        "restringido" => TRUE
    ],
    "edit_personaje" => [
        "titulo" => "Editar datos de Personaje",
        "restringido" => TRUE
    ],
    "edit_comic" => [
        "titulo" => "Editar datos de Comic",
        "restringido" => TRUE
    ],
    "delete_personaje" => [
        "titulo" => "Eliminar datos de Personaje",
        "restringido" => TRUE
    ],
    "delete_comic" => [
        "titulo" => "Eliminar datos de un Comic",
        "restringido" => TRUE
    ]
];


$seccion = $_GET['sec'] ?? "dashboard";

if (!array_key_exists($seccion, $secciones_validas)) {
    $vista = "404";
    $titulo = "404 - Página no encontrada";
} else {
    $vista = $seccion;

    if($secciones_validas[$seccion]['restringido']){
        (new Autenticacion())->verify();    
    }

    $titulo = $secciones_validas[$seccion]['titulo'];
}

$userData = $_SESSION['loggedIn'] ?? FALSE;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Tiendita de Comics :: <?= $titulo ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link href="../css/styles.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel de Administración</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                        <a class="nav-link active <?= $userData ? "" : "d-none" ?>" href="index.php?sec=dashboard">Dashboard</a>
                    </li>

                    <li class="nav-item <?= $userData ? "" : "d-none" ?>">
                        <a class="nav-link" href="index.php?sec=admin_comics">Administrar Comics</a>
                    </li>

                    <li class="nav-item <?= $userData ? "" : "d-none" ?>">
                        <a class="nav-link" href="index.php?sec=admin_personajes">Administrar Personajes</a>
                    </li>
                    <li class="nav-item <?= $userData ? "" : "d-none" ?>">
                        <a class="nav-link" href="index.php?sec=admin_series">Administrar Series</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $userData ? "" : "d-none" ?>" href="index.php?sec=admin_artistas">Administrar Artistas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $userData ? "" : "d-none" ?>" href="index.php?sec=admin_guionistas">Administrar Guionistas</a>
                    </li>

                    <li class="nav-item <?= $userData ? "d-none" : "" ?>">
                        <a class="nav-link fw-bold" href="index.php?sec=login">Login</a>
                    </li>

                    <li class="nav-item <?= $userData ? "" : "d-none" ?>">
                        <a class="nav-link fw-bold" href="actions/auth_logout.php">Logout: <span class="fw-light"><?= $userData['username'] ?? "" ?></span></a>
                    </li>

         

                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">

        <?PHP

        //Verifiquemos que el archivo exista.
        require_once file_exists("views/$vista.php") ? "views/$vista.php" : "views/404.php";

        ?>
    </main>


    <footer class="bg-info text-light text-center">
        Jorge Perez 2023
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</body>

</html>