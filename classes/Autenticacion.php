<?PHP

class Autenticacion
{

    public function log_in(string $usuario, string $password): ?bool
    {
        $datosUsuario = (new Usuario())->usuario_x_email($usuario);
    
        if ($datosUsuario) {
            // Verificar la contraseña ingresada con el hash almacenado
            if (password_verify($password, $datosUsuario->getPassword())) {                
    
                $datosLogin['username'] = $datosUsuario->getUsername();
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
    


    public function register(string $usuario, string $password, string $email, string $nombre_completo, string $adress): ?bool
    {
        $usuario_register = (new Usuario())->usuario_register($usuario, $password, $email, $nombre_completo, $adress);
    
        if ($usuario_register === null) {
            (new Alerta())->add_alerta('warning', "El usuario o correo ya está registrado.");
            return NULL;
        } elseif ($usuario_register === true) {
            (new Alerta())->add_alerta('success', "Registro exitoso. Ahora puedes iniciar sesión.");
            return TRUE;
        } else {
            (new Alerta())->add_alerta('danger', "Hubo un error en el registro. Por favor, intenta de nuevo.");
            return FALSE;
        }
    }
    
    
    




    public function log_out()
    {
     
        if (isset($_SESSION['loggedIn'])) {
            unset($_SESSION['loggedIn']);
        };
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