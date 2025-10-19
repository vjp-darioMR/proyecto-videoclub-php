<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

class Juego extends Soporte implements Resumible {
    private $consola;
    private $minNumJugadores;
    private $maxNumJugadores;

    public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxNumJugadores) {
        parent::__construct($titulo, $numero, $precio);
        $this->consola = $consola;
        $this->minNumJugadores = $minNumJugadores;
        $this->maxNumJugadores = $maxNumJugadores;
    }

    public function muestraJugadoresPosibles() {
        echo "De " . $this->minNumJugadores . " a " . $this->maxNumJugadores . " jugadores";
    }

    public function muestraResumen() {
        parent::muestraResumen();
        echo "<br>Consola: " . $this->consola;
        echo "<br>";
        $this->muestraJugadoresPosibles();
    }
}
?>