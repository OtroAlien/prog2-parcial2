<?PHP

class Autenticacion
{

    public function log_in(string $usuario, string $password): ?bool
    {
        
        
        $datosUsuario = (new Usuario())->usuario_x_email($usuario);

        (new Alerta())->get_alertas('danger', "hola");

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



public function register($email, $password, $nombre) {
    // Verificar si el usuario ya existe
    $db = new Database();
    $query = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    $result = $query->fetch();

    if ($result) {
        die("El usuario ya está registrado.");
    }

    // Insertar el nuevo usuario
    $query = $db->prepare("INSERT INTO usuarios (email, password, nombre) VALUES (:email, :password, :nombre)");
    $query->bindParam(':email', $email);
    $query->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
    $query->bindParam(':nombre', $nombre);

    if ($query->execute()) {
        header('location: ../index.php?sec=login');return true;
    } else {
        return false;
    }
}
}