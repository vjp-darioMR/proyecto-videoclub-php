<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

class Dvd extends Soporte implements Resumible {
    private $idiomas;          
    private $formatoPantalla;  

    // Constructor: inicializa el DVD
    public function __construct($titulo, $numero, $precio, $idiomas, $formatoPantalla) {
        parent::__construct($titulo, $numero, $precio);
        $this->idiomas = $idiomas;
        $this->formatoPantalla = $formatoPantalla;
    }

    // Muestra un resumen visual del DVD
    public function muestraResumen() {
        echo "<div class='col'>";
        echo "<div class='card border-warning mb-3 mx-2' style='max-width: 20rem;'>";
        echo "<div class='card-header bg-warning text-dark'>" . $this->getTitulo() . " <span class='badge rounded-pill bg-light text-dark'>" . $this->getNumero() . "</span></div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Precio: " . $this->getPrecio() . " €</h5>";
        echo "<p class='card-text'>Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . " €</p>";
        echo "<p class='card-text'><i class='bi bi-translate'></i> Idiomas: " . $this->idiomas . "</p>";
        echo "<p class='card-text'><i class='bi bi-aspect-ratio'></i> Formato: " . $this->formatoPantalla . "</p>";
        echo "</div></div></div>";
    }
}
?>