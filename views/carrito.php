<?PHP
// Obtener user_id si está logueado
$user_id = null;
if (isset($_SESSION['loggedIn']) && is_array($_SESSION['loggedIn'])) {
    $user_id = $_SESSION['loggedIn']['id'] ?? null;
}

// Crear instancia del carrito
$carritoObj = new Carrito($user_id);
$items = $carritoObj->get_carrito();
$total = $carritoObj->precio_total();
?>

<h2 class="text-center fs-2 my-5">Carrito de Compras</h2>
<div class="container my-4">

    <?PHP if (!empty($items)) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" width="15%">Imagen</th>
                    <th scope="col">Datos del producto</th>
                    <th scope="col" width="15%">Cantidad</th>
                    <th class="text-end" scope="col" width="15%">Precio Unitario</th>
                    <th class="text-end" scope="col" width="15%">Subtotal</th>
                    <th class="text-end" scope="col" width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <?PHP foreach ($items as $key => $item) { ?>
                    <tr>
                        <td><img src="img/productos/<?= $item['imagen'] ?>" alt="Imagen de <?= $item['nombre'] ?>" class="img-fluid rounded shadow-sm"></td>
                        <td class="align-middle">
                            <h3 class="h5"><?= $item['nombre'] ?></h3>
                            <p><?= $item['descripcion'] ?></p>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" onclick="updateQuantity(<?= $key ?>, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="mx-3 fw-bold quantity-display" id="quantity-<?= $key ?>"><?= $item['cantidad'] ?></span>
                                <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" onclick="updateQuantity(<?= $key ?>, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-end align-middle">
                            <p class="h5 py-3">$<?= number_format($item['precio'], 2, ",", ".") ?></p>
                        </td>
                        <td class="text-end align-middle">
                            <p class="h5 py-3 subtotal" id="subtotal-<?= $key ?>">$<?= number_format($item['cantidad'] * $item['precio'], 2, ",", ".") ?></p>
                        </td>
                        <td class="text-end align-middle">
                            <a href="admin/actions/remove_producto.php?id=<?= $key ?>" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?PHP } ?>
                <tr>
                    <td colspan="4" class="text-end">
                        <h3 class="h5 py-3">Total:</h3>
                    </td>
                    <td class="text-end">
                        <p class="h5 py-3" id="total-price">$<?= number_format($total, 2, ",", ".") ?></p>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-end gap-2">
            <a href="index.php?sec=productos" role="button" class="btn btn-danger">Seguir comprando</a>
            <a href="admin/actions/clear_items.php" role="button" class="btn btn-danger">Vaciar Carrito</a>
            <?PHP if ($user_id) { ?>
                <a href="index.php?sec=pago" role="button" class="btn btn-primary">Finalizar Compra</a>
            <?PHP } else { ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">Finalizar Compra</button>
            <?PHP } ?>
        </div>

        <script>
        // Datos de productos para JavaScript
        const productData = <?= json_encode(array_map(function($item) {
            return ['precio' => $item['precio']];
        }, $items)) ?>;

        function updateQuantity(productKey, change) {
            const quantityElement = document.getElementById('quantity-' + productKey);
            const subtotalElement = document.getElementById('subtotal-' + productKey);
            const totalElement = document.getElementById('total-price');
            
            let currentQuantity = parseInt(quantityElement.textContent);
            let newQuantity = currentQuantity + change;
            
            // No permitir cantidades menores a 1
            if (newQuantity < 1) {
                return;
            }
            
            // Actualizar la cantidad mostrada
            quantityElement.textContent = newQuantity;
            
            // Actualizar el subtotal
            const precio = productData[productKey].precio;
            const newSubtotal = newQuantity * precio;
            subtotalElement.textContent = '$' + newSubtotal.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Actualizar el total general
            updateTotal();
            
            // Enviar actualización al servidor
            updateServerQuantity(productKey, newQuantity);
        }

        function updateTotal() {
            let total = 0;
            const subtotalElements = document.querySelectorAll('.subtotal');
            
            subtotalElements.forEach(element => {
                const value = element.textContent.replace('$', '').replace(/\./g, '').replace(',', '.');
                total += parseFloat(value);
            });
            
            document.getElementById('total-price').textContent = '$' + total.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function updateServerQuantity(productKey, quantity) {
            // Crear un formulario temporal para enviar la actualización
            const formData = new FormData();
            formData.append('q[' + productKey + ']', quantity);
            
            fetch('admin/actions/update_items.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Error al actualizar la cantidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        </script>

    <?PHP } else { ?>
        <h2 class="text-center mb-5 text-danger">Su carrito está vacío</h2>
        <div class="text-center">
            <a href="index.php?sec=productos" class="btn btn-primary">Ver Productos</a>
        </div>
    <?PHP } ?>

</div>