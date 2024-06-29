<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control de Administrador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center p-5">
        <div>
            <h1 class="text-center mb-5 fw-bold">Panel de control de administrador</h1>
            <div class="list-group">
                <a href="index.php?sec=admin_productos" class="list-group-item list-group-item-action">
                    <h4 class="mb-1">Administrar Productos</h4>
                    <p class="mb-1">Editar, borrar o subir nuevos productos.</p>
                </a>
                <a href="admin_usuarios.php" class="list-group-item list-group-item-action">
                    <h4 class="mb-1">Administrar Usuarios</h4>
                    <p class="mb-1">Ver la lista de usuarios y cambiar sus roles.</p>
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
