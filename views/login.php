<div class="containerlogin">
    <div>
        <?= (new Alerta())->get_alertas(); ?>
    </div> 
    <input type="checkbox" id="check">
    <div class="login formu">
        <header>Login</header>
        <form action="admin/actions/auth_login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="pswd" placeholder="Contraseña" required="">
            <div class="boton">
                <button class="button" type="submit">Ingresar</button>
            </div>
        </form>
        <div class="signup">
            <span class="signup">¿No tenés cuenta?
                <label for="check">Registrate</label>
            </span>
        </div>
    </div>

    <div class="registration formu">
        <header>Signup</header>
        <form action="admin/actions/auth_register.php" method="POST">
        <input type="text" name="username" id="username" placeholder="Nombre de Usuario" required="">
        <input type="text" name="nombre_completo" id="nombre_completo" placeholder="Nombre Completo" required="">
        <input type="text" name="adress" id="adress" placeholder="Dirección" required="">
        <input type="email" name="email" id="email" placeholder="Email" required="">
        <input type="password" name="pswd" id="pswd" placeholder="Contraseña" required="">
        <div class="boton">
            <button class="button" type="submit">Registrar</button>
        </div>
        </form>
        <div class="signup">
        <span class="signup">¿Ya tenés cuenta?
            <label for="check">Inicia Sesión</label>
        </span>
        </div>
    </div>
</div>

<?php
// Mostrar mensaje si viene del carrito
if (isset($_SESSION['mensaje_login'])) {
    echo '<div class="alert alert-info">' . $_SESSION['mensaje_login'] . '</div>';
    unset($_SESSION['mensaje_login']);
}
?>