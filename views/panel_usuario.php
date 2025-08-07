<?php
require_once "functions/autoload.php";
$userID = $_SESSION['loggedIn']['id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    // Datos del formulario
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $localidad = $_POST['localidad'];
    $codigo_postal = $_POST['codigo_postal'];
    $pais = $_POST['pais'];
    $altura = $_POST['altura'] ?? null;

    $conexion = Conexion::getConexion();

    try {
        // Iniciar transacción
        $conexion->beginTransaction();

        // Actualizar la tabla `usuarios`

$queryUsuario = "UPDATE usuarios SET email = ? WHERE user_id = ?";
$stmtUsuario = $conexion->prepare($queryUsuario);
$stmtUsuario->execute([$email, $userID]);

// Actualizar la tabla `user_adress` (para los campos relacionados con la dirección)
$queryAddress = "UPDATE user_address 
                 SET telefono = ?, calle = ?, ciudad = ?, localidad = ?, codigo_postal = ?, pais = ?, altura = ? 
                 WHERE user_id = ?";
$stmtAddress = $conexion->prepare($queryAddress);
$stmtAddress->execute([$telefono, $calle, $ciudad, $localidad, $codigo_postal, $pais, $altura, $userID]);


        // Confirmar transacción
        $conexion->commit();

        // Agregar alerta de éxito
        (new Alerta())->add_alerta('success', "Información actualizada correctamente.");
    } catch (PDOException $e) {
        // Revertir cambios en caso de error
        $conexion->rollBack();
        (new Alerta())->add_alerta('danger', "Error al actualizar la información: " . $e->getMessage());
    }
}
?>

<div class="container">
    <!-- Alertas -->
    <div class="mb-3">
        <?= (new Alerta())->get_alertas(); ?>
    </div>

    <!-- Tarjeta de Perfil -->
    <div class="card card-transparent p-4">
        <div class="perfil-header">
            <!-- Foto de Perfil -->
            <img src="https://via.placeholder.com/150" alt="Foto de Perfil">
            
            <!-- Nombre del Usuario -->
            <h2><?= $_SESSION['loggedIn']['username'] ?></h2>
            <p>Email: <?= $_SESSION['loggedIn']['email'] ?></p>
            
 <pre>
<?php print_r($_SESSION); ?>
</pre>

            
            <!-- Botón Cerrar Sesión -->
            <form action="admin/actions/auth_logout.php" method="post">
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>

        <!-- Información de Usuario -->
        <!-- <div class="mt-4">
            <h4 class="text-center">Información Personal</h4>
        </div> -->

        <!-- Formulario de Actualización -->
        <!-- <div class="border-top pt-4">
            <h4 class="text-center mb-4">Actualizar Información</h4>
            <form action="index.php?sec=panel_usuario" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['loggedIn']['email'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $_SESSION['loggedIn']['telefono'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="calle" name="calle" value="<?= $_SESSION['loggedIn']['calle'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="altura" class="form-label">Altura (Opcional)</label>
                        <input type="number" class="form-control" id="altura" name="altura" value="<?= $_SESSION['loggedIn']['altura'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?= $_SESSION['loggedIn']['ciudad'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="localidad" class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="localidad" name="localidad" value="<?= $_SESSION['loggedIn']['localidad'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_postal" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?= $_SESSION['loggedIn']['codigo_postal'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais" value="<?= $_SESSION['loggedIn']['pais'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="actualizar_usuario" class="btn btn-primary w-50">Actualizar Información</button>
                </div>
            </form>
        </div> -->
    </div>

    <!-- Historial de Compras -->
    <div class="card card-transparent mt-5 p-4">
    <h4 class="text-center">Historial de Compras</h4>
    <?php
    $compras = [];
    if ($userID) {
        try {
            $compras = (new Compra())->compras_x_id_usuario($userID);
        } catch (Exception $e) {
            echo "<p class='text-center text-danger'>Error al cargar el historial de compras: " . $e->getMessage() . "</p>";
        }
    }

    if (empty($compras)) {
        echo "<p class='text-center'>Aún no tienes compras realizadas.</p>";
    } else {
    ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $C) { ?>
                    <tr>
                        <td><?= $C['id'] ?></td>
                        <td><?= $C['fecha'] ?></td>
                        <td><?= $C['detalle'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

</div>
