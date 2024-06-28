<?PHP

class Usuario
{

    private $id;
    private $email;
    private $username;
    private $nombre_completo;
    private $password_hash;
    private $rol;

    /**
     * Encuentra un usuario por su Username
     * @param string $username El nombre de usuario
     */
    public function usuario_x_email(string $email): ?Usuario
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM usuarios WHERE email = ?";

        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $PDOStatement->execute([$email]);

        $result = $PDOStatement->fetch();

        if (!$result) {
            return null;
        }
        return $result;
    }

    public function usuario_register(string $usuario, string $password, string $email): ?bool
    {
        $conexion = Conexion::getConexion();
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE username = :username OR email = :email");
    
        // Verificar si el usuario o el correo ya existe
        $query->bindParam(':username', $usuario);
        $query->bindParam(':email', $email);
        $query->execute();
    
        $result = $query->fetch();
    
        if (!$result) {
            // Hash de la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
            // Insertar el nuevo usuario en la base de datos
            $insertQuery = $conexion->prepare("INSERT INTO usuarios (username, password_hash, email, rol) VALUES (:username, :password_hash, :email, 'usuario')");
            $insertQuery->bindParam(':username', $usuario);
            $insertQuery->bindParam(':password_hash', $passwordHash);
            $insertQuery->bindParam(':email', $email);
    
            if ($insertQuery->execute()) {
                return true;  // Éxito en el registro
            } else {
                return false; // Error en el registro
            }
        } else {
            return null; // Usuario o correo ya registrado
        }
    }
    
    


    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getNombre_completo()
    {
        return $this->nombre_completo;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getPassword()
    {
        return $this->password_hash;
    }
}