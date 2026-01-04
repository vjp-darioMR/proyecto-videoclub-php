<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

class Juego extends Soporte implements Resumible {
    private $consola;             
    private $minNumJugadores;     
    private $maxNumJugadores;     

    // Constructor: inicializa el juego
    public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxNumJugadores) {
        parent::__construct($titulo, $numero, $precio);
        $this->consola = $consola;
        $this->minNumJugadores = $minNumJugadores;
        $this->maxNumJugadores = $maxNumJugadores;
    }

    // Muestra el rango de jugadores posibles
    public function muestraJugadoresPosibles() {
        echo "De " . $this->minNumJugadores . " a " . $this->maxNumJugadores . " jugadores";
    }

    // Muestra un resumen visual del juego
    public function muestraResumen() {
        $html = "<div class='col'>";
        $html .= "<div class='card border-info mb-3 mx-2' style='max-width: 20rem;'>";
        $html .= "<div class='card-header bg-info text-dark'>" . $this->getTitulo() . " <span class='badge rounded-pill bg-light text-dark'>" . $this->getNumero() . "</span></div>";
        $html .= "<div class='card-body'>";
        $html .= "<h5 class='card-title'>Precio: " . $this->getPrecio() . " €</h5>";
        $html .= "<p class='card-text'>Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . " €</p>";
        $html .= "<p class='card-text'><i class='bi bi-controller'></i> Consola: " . $this->consola . "</p>";
        $html .= "<p class='card-text'><i class='bi bi-people'></i> De " . $this->minNumJugadores . " a " . $this->maxNumJugadores . " jugadores</p>";
        $html .= "</div></div></div>";
        echo $html;
        return $html;
    }
}
?>