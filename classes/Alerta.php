<?php

class Alerta
{
    /**
     * Registra una alerta en el sistema, guardándola en la sesión
     * @param string $tipo El tipo de alerta (danger/warning/success)
     * @param string $mensaje El contenido de la alerta
     */
    public function add_alerta(string $tipo, string $mensaje)
    {
        if (!isset($_SESSION['alertas'])) {
            $_SESSION['alertas'] = [];
        }
        
        $_SESSION['alertas'][] = [
            'tipo' => htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8'),
            'mensaje' => htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8')
        ];
    }

    /**
     * Vacía la lista de alertas
     */
    public function clear_alertas()
    {
        $_SESSION['alertas'] = [];
    }

    /**
     * Devuelve todas las alertas acumuladas en el sistema y vacía la lista
     * @return string|null 
     */
    public function get_alertas()
    {
        if (!empty($_SESSION['alertas'])) {
            $alertasActuales = "";
            foreach ($_SESSION['alertas'] as $alerta) {
                $alertasActuales .= $this->print_alerta($alerta);
            }
            $this->clear_alertas();
            return $alertasActuales;
        } 
        return null;
    }

    /**
     * Genera el HTML para una alerta
     * @param array $alerta La alerta a imprimir
     * @return string El HTML de la alerta
     */
    private function print_alerta(array $alerta): string
    {
        $tipo = htmlspecialchars($alerta['tipo'], ENT_QUOTES, 'UTF-8');
        $mensaje = htmlspecialchars($alerta['mensaje'], ENT_QUOTES, 'UTF-8');
        
        $html = "<div class='alert alert-$tipo alert-dismissible fade show' role='alert'>";
        $html .= $mensaje;
        $html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        $html .= "</div>";

        return $html;
    }
}
