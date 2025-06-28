<?php

require_once "Conexion.php";
require_once "Categoria.php";class Descuento {

    private $id;
    private $valor;

    public function __construct($id = null, $valor = null) {
        $this->id = $id;
        $this->valor = $valor;
    }

    public static function descuentoPorId($id) {
        $conexion = Conexion::getConexion();
        $sql = "SELECT * FROM descuentos WHERE descuento_id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
            return new Descuento($fila['descuento_id'], $fila['valor']);
        }
        return null;
    }

    public function getId() {
        return $this->id;
    }

    public function getValor() {
        return $this->valor;
    }
}
?>