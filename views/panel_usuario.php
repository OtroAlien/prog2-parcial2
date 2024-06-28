<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userID = $_SESSION['loggedIn']['id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["foto_perfil"]["name"]);
    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
        $conexion = Conexion::getConexion();
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE user_id = ?";
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$target_file, $userID]);
        $_SESSION['loggedIn']['foto_perfil'] = $target_file;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $adress = $_POST['adress'];

    $conexion = Conexion::getConexion();
    $query = "UPDATE usuarios SET nombre_completo = ?, email = ?, adress = ? WHERE user_id = ?";
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([$nombre_completo, $email, $adress, $userID]);

    $_SESSION['loggedIn']['nombre_completo'] = $nombre_completo;
    $_SESSION['loggedIn']['email'] = $email;
    $_SESSION['loggedIn']['adress'] = $adress;

    (new Alerta())->add_alerta('success', "Información actualizada correctamente.");
}
?>

    <div class="container-perfil">
        <div>
            <?= (new Alerta())->get_alertas(); ?>
        </div>
        <div class="border rounded p-3 mt-4 perfil">
                <h1 class="text-center mb-5 fw-bold">Perfil</h1>
                <form action="index.php?sec=panel_usuario" method="post" enctype="multipart/form-data">
                    <div class="fotoperfil">
                        <label for="foto_perfil" class="form-label">Cambiar foto de perfil</label>
                        <input class="form-control" type="file" id="foto_perfil" name="foto_perfil">
                    </div>
                    <button type="submit" class="btn btn-primary">Subir</button>
                    <div class="nombre-usuario">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $_SESSION['loggedIn']['username'] ?>" readonly>
                        </div>
                        <div class="nombre-completo">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $_SESSION['loggedIn']['nombre_completo'] ?>" required>
                        </div>
                </form>
                <div class="text-center mt-4">
                        <form action="admin/actions/auth_logout.php" method="post">
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>

                <?php if (!empty($_SESSION['loggedIn']['foto_perfil'])): ?>
                    <div class="text-center mt-3">
                        <img src="<?= $_SESSION['loggedIn']['foto_perfil'] ?>" alt="Foto de Perfil" class="img-thumbnail" width="150">
                    </div>
                <?php endif; ?>
        </div>

                <div class="border rounded p-3 mt-4">
                    <h2 class="text-center mb-5 fw-bold">Actualizar Información</h2>
                    <form action="index.php?sec=panel_usuario" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['loggedIn']['email'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="adress" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="adress" name="adress" value="<?= $_SESSION['loggedIn']['adress'] ?>" required>
                        </div>
                        <button type="submit" name="actualizar_usuario" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>

    <div class="border rounded p-3 mt-4">
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
                    <?php foreach ($compras as $C) { ?>
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
