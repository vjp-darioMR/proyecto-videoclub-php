<?php
require_once "Resumible.php";

abstract class Soporte implements Resumible {
    private $titulo;
    private $numero;
    private $precio;
    private const IVA = 21;

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

    abstract public function muestraResumen();
}
?>