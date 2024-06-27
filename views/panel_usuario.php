<?php
session_start();
$userID = $_SESSION['loggedIn']['id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["foto_perfil"]["name"]);
    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
        $conexion = Conexion::getConexion();
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$target_file, $userID]);
        $_SESSION['loggedIn']['foto_perfil'] = $target_file; // Actualizamos la sesión
    }
}
?>

<div class="container">
    <h1 class="text-center mb-5 fw-bold">Panel de Usuario</h1>

    <div class="border rounded p-3 mb-4">
        <div>
            <?= (new Alerta())->get_alertas(); ?>
        </div>

        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5 fw-bold">Perfil</h2>

                <!-- Formulario para subir foto de perfil -->
                <form action="index.php?sec=panel_usuario" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="foto_perfil" class="form-label">Subir Foto de Perfil</label>
                        <input class="form-control" type="file" id="foto_perfil" name="foto_perfil">
                    </div>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </form>

                <!-- Mostrar la foto de perfil si existe -->
                <?php if (!empty($_SESSION['loggedIn']['foto_perfil'])): ?>
                    <div class="text-center mt-3">
                        <img src="<?= $_SESSION['loggedIn']['foto_perfil'] ?>" alt="Foto de Perfil" class="img-thumbnail" width="150">
                    </div>
                <?php endif; ?>

                <!-- Formulario para actualizar datos del usuario -->
                <div class="border rounded p-3 mt-4">
                    <h2 class="text-center mb-5 fw-bold">Actualizar Información</h2>
                    <form action="actualizar_usuario.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $_SESSION['loggedIn']['nombre'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['loggedIn']['email'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>

                <!-- Botón para desloguearse -->
                <div class="text-center mt-4">
                <div class="text-center mt-4">
                    <form action="admin/actions/auth_logout.php" method="post">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>

                </div>
            </div>
        </div>
    </div>

    <div class="border rounded p-3 mb-4">
        <h2 class="text-center mb-5 fw-bold">Historial de Compras</h2>

        <?php
        $compras = (new Compra())->compras_x_id_usuario($userID);

        if (empty($compras)) {
            echo "<p class='text-center'>Todavía no ha realizado ninguna compra.</p>";
        } else {
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="10%">ID</th>
                        <th scope="col" width="20%">Fecha</th>
                        <th class="" scope="col">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as  $C) { ?>
                        <tr>
                            <td class="align-middle">
                                <p class="h5"><?= $C['id'] ?></p>
                            </td>
                            <td class="align-middle">
                                <p class="h5"><?= $C['fecha'] ?></p>
                            </td>
                            <td class="align-middle">
                                <p><?= $C['detalle'] ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
