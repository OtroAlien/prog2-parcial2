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
            return false; 
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



     public function edit_address(array $address): bool
     {
         if (!$this->id) {
             throw new Exception("El ID del usuario no está definido.");
         }
 
         $conexion = Conexion::getConexion();
         $query = "UPDATE user_address 
                   SET calle = :calle, 
                       ciudad = :ciudad, 
                       localidad = :localidad, 
                       codigo_postal = :codigo_postal, 
                       pais = :pais, 
                       telefono = :telefono, 
                       altura = :altura 
                   WHERE user_id = :user_id";
 
         $PDOStatement = $conexion->prepare($query);
 
         try {
             $PDOStatement->execute([
                 ':user_id' => $this->id,
                 ':calle' => $address['calle'] ?? null,
                 ':ciudad' => $address['ciudad'] ?? null,
                 ':localidad' => $address['localidad'] ?? null,
                 ':codigo_postal' => $address['codigo_postal'] ?? null,
                 ':pais' => $address['pais'] ?? null,
                 ':telefono' => $address['telefono'] ?? null,
                 ':altura' => $address['altura'] ?? null,
             ]);
 
             return $PDOStatement->rowCount() > 0;
         } catch (PDOException $e) {
             throw new Exception("Error al actualizar la dirección: " . $e->getMessage());
         }
     }



    public function edit_usuario(): bool
    {

        $conexion = Conexion::getConexion();
        $query = "UPDATE usuarios 
                  SET username = :username, 
                      nombre_completo = :nombre_completo, 
                      password_hash = :password_hash, 
                      email = :email, 
                      rol = :rol, 
                      foto_perfil = :foto_perfil 
                  WHERE id = :id";

        $PDOStatement = $conexion->prepare($query);

        try {
            $PDOStatement->execute([
                ':id' => $this->id,
                ':username' => $this->username ?? null,
                ':nombre_completo' => $this->nombre_completo ?? null,
                ':password_hash' => $this->password_hash ?? null,
                ':email' => $this->email ?? null,
                ':rol' => $this->rol ?? null,
                ':foto_perfil' => $this->foto_perfil ?? null,
            ]);

            return $PDOStatement->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el usuario: " . $e->getMessage());
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
}
