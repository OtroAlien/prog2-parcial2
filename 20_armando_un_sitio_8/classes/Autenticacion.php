<?PHP

class Autenticacion
{
    public function log_in(string $usuario, string $password): ?bool
    {

        $datosUsuario = (new Usuario())->usuario_x_username($usuario);


        // echo "<pre>";
        // print_r($datosUsuario);
        // echo "</pre>";

        if ($datosUsuario) {

            // echo "<p>El usuario ingresado SI se encontró en nuestra base de datos.</p>";

            if (password_verify($password, $datosUsuario->getPassword())) {
                // echo "<p>EL PASSWORD ES CORRECTO! LOGUEAR!</p>";
                

                $datosLogin['username'] = $datosUsuario->getNombre_usuario();
                $datosLogin['nombre_completo'] = $datosUsuario->getNombre_completo();
                $datosLogin['id'] = $datosUsuario->getId();
                $datosLogin['rol'] = $datosUsuario->getRol();
                $_SESSION['loggedIn'] = $datosLogin;

                return $datosLogin['rol'];
            } else {
                (new Alerta())->add_alerta('danger', "El password ingresado no es correcto.");
                return FALSE;
            }
        } else {
            (new Alerta())->add_alerta('warning', "El usuario ingresado no se encontró en nuestra base de datos.");
            return NULL;
        }
    }

    public function log_out()
    {
     
        if (isset($_SESSION['loggedIn'])) {
            unset($_SESSION['loggedIn']);
        };
        session_destroy();
    }

    public function verify($admin = TRUE): bool
    {
      
        if (isset($_SESSION['loggedIn'])) {

            if($admin){

                if ($_SESSION['loggedIn']['rol'] == "admin" OR $_SESSION['loggedIn']['rol'] == "superadmin"){
                    return TRUE;
                }else{
                    (new Alerta())->add_alerta('warning', "El usuario no tiene permisos para ingresar a este area");
                    header('location: index.php?sec=login');
                }

            }else{
                return TRUE;
            }
        } else {
            header('location: index.php?sec=login');
        }
    }
}
