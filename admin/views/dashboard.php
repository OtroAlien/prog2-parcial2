
    <div class="container-admprofile">
        <div>
            <h2 class="text-center mb-5 fw-bold">Panel de control de administrador</h2>
            <div class="list-group">
                <a href="index.php?sec=admin_productos" class="list-group-item list-group-item-action">
                    <h3 class="mb-1">Administrar Productos</h3>
                    <p class="mb-1">Editar, borrar o subir nuevos productos.</p>
                </a>
                <a href="index.php?admin_usuarios.php" class="list-group-item list-group-item-action" >
                    <h3 class="mb-1">Administrar Usuarios</h3>
                    <p class="mb-1">Ver la lista de usuarios y cambiar sus roles.</p>
                </a>
            </div>
            <div class="mt-4 text-center">
                <form action="../admin/actions/auth_logout.php" method="POST">
                    <button type="submit" class="btn btn-danger">Desloguearse</button>
                </form>
            </div>
        </div>
    </div>