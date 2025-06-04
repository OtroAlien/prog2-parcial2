<?php
require_once "../functions/autoload.php";

// Obtener todos los usuarios
$usuarios = [];
try {
    $usuarios = Usuario::obtenerTodos();
} catch (Exception $e) {
    echo "<p class='text-center text-danger'>Error al cargar los usuarios: " . $e->getMessage() . "</p>";
}
?>

<div class="container-admp">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-3 fw-bold">Administración de Usuarios</h2>
            
            <div class="d-flex justify-content-between mb-3">
                <a href="../admin/index.php" class="btn btn-outline-success"><i class="fas fa-arrow-left me-2"></i>Atrás</a>
                <div class="input-group w-50">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="buscadorUsuarios" class="form-control" placeholder="Buscar usuarios por nombre, email o rol...">
                </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
            </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>

            <?php if (empty($usuarios)): ?>
                <div class="alert alert-info">
                    <p class="text-center">No hay usuarios registrados en el sistema.</p>
                </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col" class="sortable" data-column="username">Usuario <span class="sort-icon"></span></th>
                                <th scope="col" class="sortable" data-column="nombre_completo">Nombre Completo <span class="sort-icon"></span></th>
                                <th scope="col" class="sortable" data-column="email">Email <span class="sort-icon"></span></th>
                                <th scope="col" class="sortable" data-column="rol">Rol <span class="sort-icon"></span></th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario->getId()) ?></td>
                                    <td><?= htmlspecialchars($usuario->getUsername()) ?></td>
                                    <td><?= htmlspecialchars($usuario->getNombreCompleto()) ?></td>
                                    <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                                    <td>
                                        <select class="form-select form-select-sm rol-selector" data-user-id="<?= htmlspecialchars($usuario->getId()) ?>">
                                            <option value="usuario" <?= $usuario->getRol() == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                            <option value="admin" <?= $usuario->getRol() == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="superadmin" <?= $usuario->getRol() == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="index.php?sec=edit_usuario&id=<?= htmlspecialchars($usuario->getId()) ?>" class="text-primary" title="Editar usuario">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
                                            </a>
                                        </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
            <?php endif; ?>
        </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda de usuarios
        const buscadorUsuarios = document.getElementById('buscadorUsuarios');
        if (buscadorUsuarios) {
            buscadorUsuarios.addEventListener('keyup', function() {
                const textoBusqueda = this.value.toLowerCase();
                const filasUsuarios = document.querySelectorAll('tbody tr');
                
                filasUsuarios.forEach(fila => {
                    const username = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nombreCompleto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const rol = fila.querySelector('td:nth-child(5)').querySelector('select').value.toLowerCase();
                    
                    if (username.includes(textoBusqueda) || 
                        nombreCompleto.includes(textoBusqueda) || 
                        email.includes(textoBusqueda) ||
                        rol.includes(textoBusqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
        
        // Funcionalidad para cambiar roles de usuario
        const rolSelectors = document.querySelectorAll('.rol-selector');
        rolSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newRol = this.value;
                const selectElement = this;
                
                // Deshabilitar el selector mientras se procesa
                selectElement.disabled = true;
                
                // Crear un objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('rol', newRol);
                
                // Enviar la solicitud AJAX
                fetch('../admin/actions/update_rol.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        // Cambiar el color del select según el rol seleccionado
                        if (newRol === 'admin') {
                            selectElement.classList.add('text-danger', 'fw-bold');
                        } else if (newRol === 'superadmin') {
                            selectElement.classList.add('text-warning', 'fw-bold');
                        } else {
                            selectElement.classList.add('text-primary');
                        }
                        
                        // Mostrar mensaje de éxito
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Éxito!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                        
                        // Eliminar la alerta después de 3 segundos
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        // Mostrar mensaje de error
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>¡Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-admp').prepend(alertDiv);
                    }
                })
                .catch(error => {
                    // Habilitar el selector nuevamente
                    selectElement.disabled = false;
                    
                    // Mostrar mensaje de error
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <strong>¡Error!</strong> Hubo un problema al actualizar el rol: ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-admp').prepend(alertDiv);
                });
            });
        });
    });
</script>
