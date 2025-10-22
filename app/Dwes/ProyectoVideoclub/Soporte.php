<?php
namespace Dwes\ProyectoVideoclub;
require_once "Resumible.php";

abstract class Soporte implements Resumible {
    private $titulo;
    private $numero;
    private $precio;
    private const IVA = 21;

    //Propiedad pública para saber si el soporte está alquilado o no
    //Como es pública, se puede acceder y modificar directamente, sin getter ni setter.
    public $alquilado = false;

    public function __construct($titulo, $numero, $precio) {
        $this->titulo = $titulo;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getPrecioConIVA() {
        return $this->precio * (1 + self::IVA / 100);
    }

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