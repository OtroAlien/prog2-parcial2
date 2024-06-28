<?PHP

class Autenticacion
{

    public function log_in(string $usuario, string $password): ?bool
    {
        $datosUsuario = (new Usuario())->usuario_x_email($usuario);

        if ($datosUsuario) {

            if ($password === $datosUsuario->getPassword()) {                

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


    public function register(string $usuario, string $password, string $email): ?bool
    {
        
        $usuario_register = (new Usuario())->usuario_register($usuario, $password, $email);

        
        if (!$usuario_register) {
            (new Alerta())->add_alerta('warning', "El usuario o correo ya está registrado.");
            return NULL;
        }else if($usuario_register){
            (new Alerta())->add_alerta('success', "Registro exitoso. Ahora puedes iniciar sesión.");
            return TRUE;
        } else {
            (new Alerta())->add_alerta('danger', "Hubo un error en el registro. Por favor, intenta de nuevo.");
            return FALSE;
        }

        // Hash de la contraseña
        // $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        
        // $query->bindParam(':email', $email);
        // $query->bindParam(':adress', $adress);
    }


    // public function register($email, $password, $nombre) {
    //     // Verificar si el usuario ya existe
    //     $db = new Database();
    //     $query = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
    //     $query->bindParam(':email', $email);
    //     $query->execute();
    //     $result = $query->fetch();
    
    //     if ($result) {
    //         die("El usuario ya está registrado.");
    //     }
    
    //     // Insertar el nuevo usuario
    //     $query = $db->prepare("INSERT INTO usuarios (email, password, nombre) VALUES (:email, :password, :nombre)");
    //     $query->bindParam(':email', $email);
    //     $query->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
    //     $query->bindParam(':nombre', $nombre);
    
    //     if ($query->execute()) {
    //         header('location: ../index.php?sec=login');return true;
    //     } else {
    //         return false;
    //     }
    // }





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