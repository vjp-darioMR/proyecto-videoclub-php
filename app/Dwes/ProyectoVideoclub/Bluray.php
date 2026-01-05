<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

class Bluray extends Soporte implements Resumible {
    private $duracion;
    private $is4k;

    // Constructor: inicializa el Bluray
    public function __construct($titulo, $numero, $precio, $duracion, $is4k) {
        parent::__construct($titulo, $numero, $precio);
        $this->duracion = $duracion;
        $this->is4k = $is4k;
    }

    // Obtiene la duración del Bluray en minutos
    public function getDuracion() {
        return $this->duracion;
    }

    // Obtiene si es 4K
    public function getIs4k() {
        return $this->is4k;
    }

    // Muestra un resumen visual del Bluray
    public function muestraResumen() {
        $html = "<div class='col'>";
        $html .= "<div class='card border-info mb-3 mx-2' style='max-width: 20rem;'>";
        $html .= "<div class='card-header bg-info text-dark'>" . $this->getTitulo() . " <span class='badge rounded-pill bg-light text-dark'>" . $this->getNumero() . "</span>";
        if ($this->is4k) {
            $html .= " <span class='badge rounded-pill bg-danger'>4K</span>";
        }
        $html .= "</div>";
        $html .= "<div class='card-body'>";
        $html .= "<h5 class='card-title'>Precio: " . $this->getPrecio() . " €</h5>";
        $html .= "<p class='card-text'>Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . " €</p>";
        $html .= "<p class='card-text'><i class='bi bi-clock'></i> Duración: " . $this->duracion . " minutos</p>";
        $html .= "</div></div></div>";
        echo $html;
        return $html;
    }
}
?>
