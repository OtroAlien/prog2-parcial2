<div class="containerlogin">
    <div class="loginform">  	
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <div>
                <?= (new Alerta())->get_alertas(); ?>
            </div> 
            <form action="admin/actions/auth_register.php" method="POST">
                <label for="chk" aria-hidden="true">Registrate</label>
                <input type="text" name="username" id="username" placeholder="Nombre de Usuario" required="">
                <input type="text" name="nombre_completo" id="nombre_completo" placeholder="Nombre Completo" required="">
                <input type="text" name="adress" id="adress" placeholder="Dirección" required="">
                <input type="email" name="email" id="email" placeholder="Email" required="">
                <input type="password" name="pswd" id="pswd" placeholder="Contraseña" required="">
                <div class="boton">
                    <button class="button" type="submit">Registrar</button>
                </div>
            </form>
        </div>

        <div class="login">
            <form action="admin/actions/auth_login.php" method="POST">
                <label for="chk" aria-hidden="true">Iniciar Sesión</label>
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="pswd" placeholder="Contraseña" required="">
                <div class="boton">
                    <button class="button" type="submit">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
