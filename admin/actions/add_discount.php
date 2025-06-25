<?php
require_once "../../functions/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['nombre'] ?? '';

    try {
        if ($valor === '') {
            throw new Exception("El valor del descuento no puede estar vacío.");
        }

        // Validar que sea un número entre 0 y 100
        if (!is_numeric($valor) || $valor < 0 || $valor > 100) {
            throw new Exception("El descuento debe ser un número entre 0 y 100.");
        }

        $db = (new Conexion())->getConexion();

        // Verificar si ya existe el valor de descuento
        $query = $db->prepare("SELECT COUNT(*) FROM descuentos WHERE valor = ?");
        $query->execute([$valor]);
        if ($query->fetchColumn()) {
            throw new Exception("El descuento '$valor%' ya existe.");
        }

        // Insertar nuevo descuento en la tabla
        $insert = $db->prepare("INSERT INTO descuentos (valor) VALUES (?)");
        $insert->execute([$valor]);

        (new Alerta())->add_alerta('success', "Descuento '$valor%' agregado correctamente.");
    } catch (Exception $e) {
        (new Alerta())->add_alerta('danger', $e->getMessage());
    }
}

header('Location: ../index.php?sec=admin_productos');
exit;