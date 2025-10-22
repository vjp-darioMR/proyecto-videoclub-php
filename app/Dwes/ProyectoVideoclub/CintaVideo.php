<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

class CintaVideo extends Soporte implements Resumible {
    private $duracion; // Duración de la cinta en minutos

    // Constructor: inicializa la cinta de vídeo
    public function __construct($titulo, $numero, $precio, $duracion) {
        parent::__construct($titulo, $numero, $precio);
        $this->duracion = $duracion;
    }

    // Muestra un resumen visual de la cinta de vídeo
    public function muestraResumen() {
        echo "<div class='col'>";
        echo "<div class='card border-success mb-3 mx-2' style='max-width: 20rem;'>";
        echo "<div class='card-header bg-success'>" . $this->getTitulo() . " <span class='badge rounded-pill bg-success'>" . $this->getNumero() . "</span></div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Precio: " . $this->getPrecio() . " €</h5>";
        echo "<p class='card-text'>Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . " €</p>";
        echo "<p class='card-text'><i class='bi bi-clock'></i> Duración: " . $this->duracion . " minutos</p>";
        echo "</div></div></div>";
    }
}
?>