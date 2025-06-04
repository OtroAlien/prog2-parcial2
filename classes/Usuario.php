<?php

require_once "Conexion.php";

class Usuario
{
    private $id;
    private $username;
    private $nombre_completo;
    private $password_hash;
    private $email;
    private $rol;
    private $foto_perfil;
    private $address;

    private static $createValues = [
        'id', 
        'username', 
        'nombre_completo', 
        'password_hash', 
        'email', 
        'rol',
        'foto_perfil'
    ];

// Función para obtener el ID del usuario
function obtenerIdUsuario($userID) {
    $conexion = Conexion::getConexion(); // Conexión a la base de datos

    try {
        // Consulta para obtener el ID del usuario
        $query = "SELECT id FROM usuarios WHERE user_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$userID]);

        // Obtener el resultado
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario['id']; // Retornar el ID
        } else {
            return null; // Usuario no encontrado
        }
    } catch (PDOException $e) {
        echo "Error al obtener el ID del usuario: " . $e->getMessage();
        return null;
    }
}


    public function usuario_x_email(string $email): ?self
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $PDOStatement = $conexion->prepare($query);
        $PDOStatement->execute([$email]);

        $result = $PDOStatement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        $usuario = new self();
        foreach ($result as $key => $value) {
            if (property_exists($usuario, $key)) {
                $usuario->$key = $value;
            }
        }
        return $usuario;
    }

    public function usuario_register(string $username, string $password, string $email, string $nombre_completo): bool
    {
        $conexion = Conexion::getConexion();

        $query = $conexion->prepare("SELECT * FROM usuarios WHERE username = :username OR email = :email");
        $query->bindParam(':username', $username);
        $query->bindParam(':email', $email);
        $query->execute();

        if ($query->fetch()) {
            return false; // Usuario ya existe
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = $conexion->prepare(
            "INSERT INTO usuarios (username, password_hash, email, nombre_completo, rol) 
            VALUES (:username, :password_hash, :email, :nombre_completo, 'usuario')"
        );
        $insertQuery->bindParam(':username', $username);
        $insertQuery->bindParam(':password_hash', $passwordHash);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':nombre_completo', $nombre_completo);

        return $insertQuery->execute();
    }

    public function edit_usuario(array $usuario, array $id_address): bool
    {
        $conexion = Conexion::getConexion();

        try {
            $queryUsuario = "UPDATE usuarios 
                SET username = :username, 
                    nombre_completo = :nombre_completo, 
                    email = :email, 
                    rol = :rol, 
                    foto_perfil = :foto_perfil,
                    id_address = :id_address
                WHERE user_id = :user_id";

            $stmtUsuario = $conexion->prepare($queryUsuario);
            $stmtUsuario->execute([
                ':user_id' => $this->id,
                ':username' => $usuario['username'] ?? $this->username,
                ':nombre_completo' => $usuario['nombre_completo'] ?? $this->nombre_completo,
                ':email' => $usuario['email'] ?? $this->email,
                ':rol' => $usuario['rol'] ?? $this->rol,
                ':foto_perfil' => $usuario['foto_perfil'] ?? $this->foto_perfil,
                ':id_address' => $id_address ?? $this->address
            ]);

            $queryAddress = "UPDATE user_address 
                    SET calle = :calle, 
                        ciudad = :ciudad, 
                        localidad = :localidad, 
                        codigo_postal = :codigo_postal, 
                        pais = :pais, 
                        telefono = :telefono, 
                        altura = :altura 
                    WHERE user_id = :user_id";

            $stmtAddress = $conexion->prepare($queryAddress);
            $stmtAddress->execute([
                ':user_id' => $this->id,
                ':calle' => $id_address['calle'] ?? null,
                ':ciudad' => $id_address['ciudad'] ?? null,
                ':localidad' => $id_address['localidad'] ?? null,
                ':codigo_postal' => $id_address['codigo_postal'] ?? null,
                ':pais' => $id_address['pais'] ?? null,
                ':telefono' => $id_address['telefono'] ?? null,
                ':altura' => $id_address['altura'] ?? null,
            ]);

            $conexion->commit();
            return true;
        } catch (PDOException $e) {
            $conexion->rollBack();
            throw new Exception("Error al actualizar el usuario y dirección: " . $e->getMessage());
        }
    }

    public function getUsuarioConDireccion(int $user_id): ?array
    {
        $conexion = Conexion::getConexion();

        // Corregir la consulta, usando 'user_id' en la cláusula WHERE
        $query = "SELECT u.*, a.calle, a.ciudad, a.localidad, a.codigo_postal, a.pais, a.telefono, a.altura 
                  FROM usuarios u
                  LEFT JOIN user_address a ON u.user_id = a.user_id
                  WHERE u.user_id = :user_id";

        $stmt = $conexion->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : null;
    }

    public function cargarUsuario(int $user_id): ?self
    {
        $data = $this->getUsuarioConDireccion($user_id);

        if (!$data) {
            return null;
        }

        $this->id = $data['user_id'];
        $this->username = $data['username'];
        $this->nombre_completo = $data['nombre_completo'];
        $this->password_hash = $data['password_hash'];
        $this->email = $data['email'];
        $this->rol = $data['rol'];
        $this->foto_perfil = $data['foto_perfil'];
        $this->address = [
            'calle' => $data['calle'],
            'ciudad' => $data['ciudad'],
            'localidad' => $data['localidad'],
            'codigo_postal' => $data['codigo_postal'],
            'pais' => $data['pais'],
            'telefono' => $data['telefono'],
            'altura' => $data['altura'],
        ];

        return $this;
    }

    // Getters
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

    public function getNombreCompleto()
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

    public function getFotoPerfil()
    {
        return $this->foto_perfil;
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Obtiene todos los usuarios de la base de datos
     * @return array Array de objetos Usuario
     */
    public static function obtenerTodos(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM usuarios ORDER BY user_id ASC";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $usuarios = [];
        while ($datos = $stmt->fetch()) {
            $usuario = new self();
            $usuario->id = $datos['user_id'];
            $usuario->username = $datos['username'];
            $usuario->nombre_completo = $datos['nombre_completo'];
            $usuario->password_hash = $datos['password_hash'];
            $usuario->email = $datos['email'];
            $usuario->rol = $datos['rol'];
            $usuarios[] = $usuario;
        }
        
        return $usuarios;
    }
    
    /**
     * Obtiene todos los roles disponibles en la base de datos
     * @return array Array de roles únicos
     */
    public static function obtenerRoles(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT DISTINCT rol FROM usuarios ORDER BY FIELD(rol, 'superadmin', 'admin', 'usuario')";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        
        $roles = [];
        while ($rol = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $rol['rol'];
        }
        
        return $roles;
    }
}
