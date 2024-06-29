<?PHP

class Usuario
{

    private $id;
    private $username;
    private $nombre_completo;
    private $password_hash;
    private $email;
    private $adress;
    private $rol;
    private $foto_perfil;

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


        public function usuario_register(string $usuario, string $password, string $email, string $nombre_completo, string $adress): ?bool
        {
            $conexion = Conexion::getConexion();
            $query = $conexion->prepare("SELECT * FROM usuarios WHERE username = :username OR email = :email");
            $query->bindParam(':username', $usuario);
            $query->bindParam(':email', $email);
            $query->execute();
        
            $result = $query->fetch();
        
            if (!$result) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $insertQuery = $conexion->prepare("INSERT INTO usuarios (username, password_hash, email, nombre_completo, adress, rol) VALUES (:username, :password_hash, :email, :nombre_completo, :adress, 'usuario')");
                $insertQuery->bindParam(':username', $usuario);
                $insertQuery->bindParam(':password_hash', $passwordHash);
                $insertQuery->bindParam(':email', $email);
                $insertQuery->bindParam(':nombre_completo', $nombre_completo);
                $insertQuery->bindParam(':adress', $adress);

                if ($insertQuery->execute()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return null;
            }
        }
        

        public function edit_usuario($usuario)
        {
    
            $conexion = Conexion::getConexion();
            $query = "UPDATE usuario SET username = :username, nombre_completo = :nombre_completo, password_hash = :password_hash, email = :email, adress = :adress, foto_perfil = :foto_perfil, rol = :rol WHERE id = :id";
    
            $PDOStatement = $conexion->prepare($query);
            $PDOStatement->execute(
                [
                    'id' => $this->id,
                    'username' => $username,
                    'nombre_completo' => $nombre_completo,
                    'password_hash' => $password_hash,
                    'email' => $email,
                    'adress' => $adress,
                    'foto_perfil' => $foto_perfil,
                    'rol' => $rol
                ]
            );
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

    public function getAdress()
    {
        return $this->adress;
    }

    public function getFoto_perfil()
    {
        return $this->foto_perfil;
    }
}