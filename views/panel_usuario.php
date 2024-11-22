<?php
require_once "functions/autoload.php";

$userID = $_SESSION['loggedIn']['id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $calle = trim($_POST['calle']);
    $ciudad = trim($_POST['ciudad']);
    $localidad = trim($_POST['localidad']);
    $codigo_postal = trim($_POST['codigo_postal']);
    $pais = trim($_POST['pais']);
    $altura = $_POST['altura'] ?? null;

    if (empty($email)) {
        (new Alerta())->add_alerta('danger', "El email es obligatorio.");
    } else {
        $conexion = Conexion::getConexion();
        try {
            $conexion->beginTransaction();

            $queryUsuario = "UPDATE usuarios SET email = ? WHERE user_id = ?";
            $stmtUsuario = $conexion->prepare($queryUsuario);
            $stmtUsuario->execute([$email, $userID]);

            $queryAddress = "UPDATE user_address 
                             SET telefono = ?, calle = ?, ciudad = ?, localidad = ?, codigo_postal = ?, pais = ?, altura = ? 
                             WHERE user_id = ?";
            $stmtAddress = $conexion->prepare($queryAddress);
            $stmtAddress->execute([$telefono, $calle, $ciudad, $localidad, $codigo_postal, $pais, $altura, $userID]);

            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES['foto_perfil']['type'], $allowedTypes)) {
                    (new Alerta())->add_alerta('danger', "Tipo de archivo no permitido.");
                    exit;
                }

                $targetDir = "uploads/profile_pictures/";
                $targetFile = $targetDir . basename($_FILES["foto_perfil"]["name"]);

                if (file_exists($targetFile)) {
                    (new Alerta())->add_alerta('danger', "El archivo ya existe.");
                    exit;
                }

                if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $targetFile)) {
                    $queryFoto = "UPDATE usuarios SET foto = ? WHERE user_id = ?";
                    $stmtFoto = $conexion->prepare($queryFoto);
                    $stmtFoto->execute([$targetFile, $userID]);
                } else {
                    throw new Exception("Error al cargar la foto.");
                }
            }

            $conexion->commit();
            (new Alerta())->add_alerta('success', "Información actualizada correctamente.");
        } catch (PDOException $e) {
            $conexion->rollBack();
            (new Alerta())->add_alerta('danger', "Error al actualizar la información: " . $e->getMessage());
        }
    }
}

$usuario = (new Usuario())->getId($userID);
?>

<div class="container-admp">
    <div class="row">
        <div class="col">

            <div class="card card-transparent p-4">
                <div class="perfil-header">
                    <img src="<?= $_SESSION['loggedIn']['foto'] ?? 'https://via.placeholder.com/150' ?>" alt="Foto de Perfil" class="img-thumbnail rounded">
                    
                    <p><strong>Usuario:</strong> <?= $_SESSION['loggedIn']['username'] ?></p>
                    <p><strong>Email:</strong> <?= $_SESSION['loggedIn']['email'] ?></p>

                    <form action="admin/actions/auth_logout.php" method="post">
                        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                    </form>
                </div>

                <div class="border-top pt-4">
                    <h4 class="text-center mb-4">Actualizar Información</h4>
                    <form action="actions/edit_usuario.php?" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['loggedIn']['email'] ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $usuario['telefono'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="calle" class="form-label">Calle</label>
                                <input type="text" class="form-control" id="calle" name="calle" value="<?= $usuario['calle'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="altura" class="form-label">Altura</label>
                                <input type="number" class="form-control" id="altura" name="altura" value="<?= $usuario['altura'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?= $usuario['ciudad'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="localidad" class="form-label">Localidad</label>
                                <input type="text" class="form-control" id="localidad" name="localidad" value="<?= $usuario['localidad'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?= $usuario['codigo_postal'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" value="<?= $usuario['pais'] ?? '' ?>"   >
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                                <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="actualizar_usuario" class="btn btn-primary w-50">Actualizar Información</button>
                        </div>
                    </form>
                </div>
            </div>

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
                            <?php foreach ($compras as $compra) { ?>
                                <tr>
                                    <td><?= $compra['id_compra'] ?></td>
                                    <td><?= date("d/m/Y", strtotime($compra['fecha'])) ?></td>
                                    <td><a href="compra_detalle.php?id=<?= $compra['id_compra'] ?>" class="btn btn-link">Ver Detalle</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
