<?php
require_once "functions/autoload.php";
$userID = $_SESSION['loggedIn']['id'] ?? false;

// Obtener datos completos del usuario con dirección
$usuario = null;
$datosUsuario = [];
if ($userID) {
    $usuario = new Usuario();
    $datosUsuario = $usuario->getUsuarioConDireccion($userID);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    // Datos del formulario
    $username = $_POST['username'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $localidad = $_POST['localidad'];
    $codigo_postal = $_POST['codigo_postal'];
    $pais = $_POST['pais'];
    $altura = $_POST['altura'] ?? null;

    try {
        // Usar la clase Usuario para actualizar
        $usuarioObj = new Usuario();
        $usuarioObj->cargarUsuario($userID);
        
        $resultado = $usuarioObj->edit_usuario(
            [
                'username' => $username,
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'rol' => $datosUsuario['rol'] ?? 'usuario',
                'foto_perfil' => $datosUsuario['foto_perfil'] ?? null
            ],
            [
                'calle' => $calle,
                'ciudad' => $ciudad,
                'localidad' => $localidad,
                'codigo_postal' => $codigo_postal,
                'pais' => $pais,
                'telefono' => $telefono,
                'altura' => $altura
            ]
        );

        if ($resultado) {
            // Actualizar datos en sesión
            $_SESSION['loggedIn']['username'] = $username;
            $_SESSION['loggedIn']['email'] = $email;
            
            (new Alerta())->add_alerta('success', "Información actualizada correctamente.");
            
            // Recargar datos actualizados
            $datosUsuario = $usuario->getUsuarioConDireccion($userID);
        }
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', "Error al actualizar la información: " . $e->getMessage());
    }
}
?>

<div class="container my-5">
    <!-- Alertas -->
    <div class="mb-4">
        <?= (new Alerta())->get_alertas(); ?>
    </div>

    <div class="row">
        <!-- Panel de Información Personal -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <!-- Foto de Perfil -->
                    <div class="mb-3">
                        <img src="<?= !empty($datosUsuario['foto_perfil']) ? 'img/' . $datosUsuario['foto_perfil'] : 'https://via.placeholder.com/120' ?>" 
                             alt="Foto de Perfil" 
                             class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--bs-primary);">
                    </div>
                    
                    <!-- Información Básica -->
                    <h4 class="fw-bold text-primary mb-1"><?= htmlspecialchars($datosUsuario['username'] ?? 'Usuario') ?></h4>
                    <p class="text-muted mb-2"><?= htmlspecialchars($datosUsuario['nombre_completo'] ?? '') ?></p>
                    <p class="text-muted mb-3"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($datosUsuario['email'] ?? '') ?></p>
                    
                    <!-- Rol Badge -->
                    <span class="badge bg-primary mb-3">
                        <i class="fas fa-user-tag me-1"></i>
                        <?= ucfirst($datosUsuario['rol'] ?? 'usuario') ?>
                    </span>
                    
                    <!-- Botón Cerrar Sesión -->
                    <div class="d-grid">
                        <form action="admin/actions/auth_logout.php" method="post">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Edición de Datos -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Información Personal</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?sec=panel_usuario" method="post">
                        <!-- Información Personal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-user me-2"></i>Datos Personales</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-semibold">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?= htmlspecialchars($datosUsuario['username'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombre_completo" class="form-label fw-semibold">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                                           value="<?= htmlspecialchars($datosUsuario['nombre_completo'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($datosUsuario['email'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="telefono" name="telefono" 
                                           value="<?= htmlspecialchars($datosUsuario['telefono'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Información de Dirección -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-map-marker-alt me-2"></i>Dirección</h6>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="calle" class="form-label fw-semibold">Calle</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-road"></i></span>
                                    <input type="text" class="form-control" id="calle" name="calle" 
                                           value="<?= htmlspecialchars($datosUsuario['calle'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="altura" class="form-label fw-semibold">Altura</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="number" class="form-control" id="altura" name="altura" 
                                           value="<?= htmlspecialchars($datosUsuario['altura'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label fw-semibold">Ciudad</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                           value="<?= htmlspecialchars($datosUsuario['ciudad'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="localidad" class="form-label fw-semibold">Localidad</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                    <input type="text" class="form-control" id="localidad" name="localidad" 
                                           value="<?= htmlspecialchars($datosUsuario['localidad'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigo_postal" class="form-label fw-semibold">Código Postal</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                           value="<?= htmlspecialchars($datosUsuario['codigo_postal'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label fw-semibold">País</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="text" class="form-control" id="pais" name="pais" 
                                           value="<?= htmlspecialchars($datosUsuario['pais'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Botón de Actualización -->
                        <div class="d-grid">
                            <button type="submit" name="actualizar_usuario" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Actualizar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Compras -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Historial de Compras</h5>
                </div>
                <div class="card-body">
                    <?php
                    $compras = [];
                    if ($userID) {
                        try {
                            $compras = (new Compra())->compras_x_id_usuario($userID);
                        } catch (Exception $e) {
                            echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle me-2'></i>Error al cargar el historial de compras: " . $e->getMessage() . "</div>";
                        }
                    }

                    if (empty($compras)) {
                        echo "<div class='text-center py-5'>";
                        echo "<i class='fas fa-shopping-cart fa-3x text-muted mb-3'></i>";
                        echo "<p class='text-muted fs-5'>Aún no tienes compras realizadas.</p>";
                        echo "<a href='index.php?sec=productos' class='btn btn-primary'><i class='fas fa-shopping-bag me-2'></i>Explorar Productos</a>";
                        echo "</div>";
                    } else {
                    ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag me-2"></i>ID Orden</th>
                                        <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                        <th><i class="fas fa-list me-2"></i>Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($compras as $compra) { ?>
                                        <tr>
                                            <td><span class="badge bg-primary">#<?= $compra['id'] ?></span></td>
                                            <td><?= date('d/m/Y H:i', strtotime($compra['fecha'])) ?></td>
                                            <td><?= htmlspecialchars($compra['detalle']) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.input-group-text {
    background-color: var(--bs-light);
    border-color: var(--bs-border-color);
}

.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.table-hover tbody tr:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.badge {
    font-size: 0.85em;
}
</style>
