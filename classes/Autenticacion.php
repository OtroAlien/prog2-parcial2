<?php

class Autenticacion
{
    public function __construct() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function log_in(string $usuario, string $password): ?string
    {
        $datosUsuario = (new Usuario())->usuario_x_email($usuario);
    
        if ($datosUsuario) {
            if (password_verify($password, $datosUsuario->getPassword())) {
                // Crear y almacenar información de sesión
                $datosLogin['username'] = $datosUsuario->getUsername();
                $datosLogin['nombre_completo'] = $datosUsuario->getNombreCompleto();
                $datosLogin['email'] = $datosUsuario->getEmail();
                $datosLogin['address'] = $datosUsuario->getAddress();
                $datosLogin['id'] = $datosUsuario->getId();
                $datosLogin['rol'] = $datosUsuario->getRol();
                $_SESSION['loggedIn'] = $datosLogin;
                
                // Crear instancia del carrito para manejar la migración
                $carrito = new Carrito($datosLogin['id']);
                
                // Cargar carrito desde la base de datos a la sesión
                $carrito->cargarCarritoASession();
    
                return $datosLogin['rol'];
            } else {
                (new Alerta())->add_alerta('danger', "El password ingresado no es correcto.");
                return null;
            }
        } else {
            (new Alerta())->add_alerta('warning', "El usuario ingresado no se encontró en nuestra base de datos.");
            return null;
        }
    }

    public function register(string $usuario, string $password, string $email, string $nombre_completo, string $address): ?bool
    {
        $usuario_register = (new Usuario())->usuario_register($usuario, $password, $email, $nombre_completo, $address);
    
        if ($usuario_register === null) {
            (new Alerta())->add_alerta('warning', "El usuario o correo ya está registrado.");
            return null; // Corregido: minúsculas
        } elseif ($usuario_register === true) {
            (new Alerta())->add_alerta('success', "Registro exitoso. Ahora puedes iniciar sesión.");
            return true; // Corregido: minúsculas
        } else {
            (new Alerta())->add_alerta('danger', "Hubo un error en el registro. Por favor, intenta de nuevo.");
            return false; // Corregido: minúsculas
        }
    }

    public function log_out()
    {
        // Solo limpiar el carrito de la sesión, NO de la base de datos
        if (isset($_SESSION['carrito'])) {
            unset($_SESSION['carrito']);
        }
        
        // Limpiar la sesión del usuario
        if (isset($_SESSION['loggedIn'])) {
            unset($_SESSION['loggedIn']);
        }
    }

    public function verify(bool $admin = true): bool
    {
        if (isset($_SESSION['loggedIn'])) {
            if($admin){
                if ($_SESSION['loggedIn']['rol'] == "admin" || $_SESSION['loggedIn']['rol'] == "superadmin"){
                    return true;
                } else {
                    (new Alerta())->add_alerta('warning', "El usuario no tiene permisos para ingresar a este area");
                    header('location: index.php?sec=login');
                    exit; // Agregado exit después del redirect
                }
            } else {
                return true;
            }
        } else {
            header('location: index.php?sec=login');
            exit; // Agregado exit después del redirect
        }
        
        return false; // Backup return como recomendaste
    }
}