<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userID = $_SESSION['loggedIn']['id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $email = $_POST['email'];
    $adress = $_POST['adress'];

    $conexion = Conexion::getConexion();
    $query = "UPDATE usuarios SET email = ?, adress = ? WHERE user_id = ?";
    $PDOStatement = $conexion->prepare($query);
    $PDOStatement->execute([ $email, $adress, $userID]);

    (new Alerta())->add_alerta('success', "Información actualizada correctamente.");
}
?>

<div class="container-perfil">
    <div>
        <?= (new Alerta())->get_alertas(); ?>
    </div>
    <div class="perfil border rounded p-3 mt-4">
        <div class="perfil-info">
            <h1 class="text-center mb-5 fw-bold">Perfil</h1>
            <div class="nombre-usuario">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <h2 id="username"><?= $_SESSION['loggedIn']['username'] ?></h2>
            </div>
            <div class="nombre-completo">
                <label for="nombre_completo" class="form-label">Nombre Completo</label>
                <h2 id="nombre_completo"><?= $_SESSION['loggedIn']['nombre_completo'] ?></h2>
            </div>
            <div class="text-center mt-4">
                <form action="admin/actions/auth_logout.php" method="post">
                    <button type="submit" class="button btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <div class="actualizar-info border rounded p-3 mt-4">
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
                <div class="text-center mt-4">
                    <button type="submit" name="actualizar_usuario" class="button btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="historial-compras border rounded p-3 mt-4">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert .close').forEach(function(button) {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
});
</script>
