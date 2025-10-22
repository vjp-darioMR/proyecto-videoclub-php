<?php
namespace Dwes\ProyectoVideoclub;
require_once "Resumible.php";

// Clase abstracta base para todos los soportes del videoclub
abstract class Soporte implements Resumible {
    private $titulo;    
    private $numero;    
    private $precio;    
    private const IVA = 21; 

    // Indica si el soporte está alquilado (pública para acceso directo)
    public $alquilado = false;

    // Constructor: inicializa el soporte
    public function __construct($titulo, $numero, $precio) {
        $this->titulo = $titulo;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    // Métodos para obtener información básica del soporte
    public function getTitulo() {
        return $this->titulo;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getPrecio() {
        return $this->precio;
    }

    // Calcula el precio con IVA
    public function getPrecioConIVA() {
        return $this->precio * (1 + self::IVA / 100);
    }

    // Muestra un resumen visual básico del soporte
    public function muestraResumen() {
        echo "<div class='col'>";
        echo "<div class='card border-success mb-3 mx-2' style='max-width: 20rem;'>";
        echo "<div class='card-header'>" . $this->getTitulo() . " <span class='badge rounded-pill bg-success'>" . $this->getNumero() . "</span></div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Precio: " . $this->getPrecio() . " €</h5>";
        echo "<p class='card-text'>Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . " €</p>";
        // Los detalles extra se añaden en las clases hijas
        echo "</div></div></div>";
    }
}
?>