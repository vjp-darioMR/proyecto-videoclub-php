<?php
require_once "Soporte.php";

class CintaVideo extends Soporte implements Resumible {
    private $duracion;

    public function __construct($titulo, $numero, $precio, $duracion) {
        parent::__construct($titulo, $numero, $precio);
        $this->duracion = $duracion;
    }

    public function muestraResumen() {
        parent::muestraResumen();
        echo "<br>Duración: " . $this->duracion . " minutos";
    }
}
?>