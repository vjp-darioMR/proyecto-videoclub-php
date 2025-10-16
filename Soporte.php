<?php
class Soporte {
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

    public function muestraResumen() {
        echo "Título: " . $this->titulo . "<br>";
        echo "Número: " . $this->numero . "<br>";
        echo "Precio: " . $this->precio . " euros<br>";
        echo "Precio con IVA: " . $this->getPrecioConIVA() . " euros";
    }
}
?>