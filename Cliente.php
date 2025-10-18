<?php
require_once "Soporte.php";

class Cliente
{
    private $nombre;
    private $numero;
    private $maxAlquilerConcurrerte;
    private $numSoportesAlquilados = 0;
    private $soportesAlquilados = [];

    public function __construct($nombre, $numero, $maxAlquilerConcurrerte = 3)
    {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrerte = $maxAlquilerConcurrerte;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNumSoportesAlquilados()
    {
        return $this->numSoportesAlquilados;
    }


    public function tieneAlquilado($soporte)
    {
        foreach ($this->soportesAlquilados as $alquilado) {
            if ($alquilado === $soporte) {
                echo "El soporte " . $soporte->getTitulo() . " ya está alquilado.<br>";
                return true;
            }
        }
        echo "El soporte " . $soporte->getTitulo() . " no está alquilado.<br>";
        return false;
    }


    public function alquilar(Soporte $soporte)
    {
        if ($this->tieneAlquilado($soporte)) {
            echo "No se puede alquilar: el soporte ya está alquilado.<br>";
            return false;
        }
        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrerte) {
            echo "No se puede alquilar: se ha superado el maximo de " . $this->maxAlquilerConcurrerte . " alquileres.<br>";
            return false;
        }
        $this->soportesAlquilados[] = $soporte;
        $this->numSoportesAlquilados++;
        echo "Soporte " . $soporte->getTitulo() . " alquilado. Total de alquileres: " . $this->numSoportesAlquilados . "<br>";
        return $this;
    }

    public function devolver($numSoporte)
    {
        foreach ($this->soportesAlquilados as $indice => $soporte) {
            if ($soporte->getNumero() === $numSoporte) {
                unset($this->soportesAlquilados[$indice]);
                $this->soportesAlquilados = array_values($this->soportesAlquilados);
                $this->numSoportesAlquilados--;
                echo "Soporte con número " . $numSoporte . " (" . $soporte->getTitulo() . ") devuelto con éxito. Total de alquileres: " . $this->numSoportesAlquilados . "<br>";
                return true;
            }
        }
        echo "No se puede devolver: el soporte con número " . $numSoporte . " no está alquilado.<br>";
        return false;
    }

    public function listarAlquileres()
    {
        echo "Número de alquileres: " . $this->numSoportesAlquilados . "<br>";
        if ($this->numSoportesAlquilados > 0) {
            echo "Soportes alquilados:<br>";
            foreach ($this->soportesAlquilados as $soporte) {
                echo "- " . $soporte->getTitulo() . " (Número: " . $soporte->getNumero() . ")<br>";
            }
        } else {
            echo "No hay alquileres registrados.<br>";
        }
    }

    public function muestraResumen()
    {
        echo "Nombre: " . $this->nombre . "<br>";
        echo "Alquileres realizados: " . $this->numSoportesAlquilados . "<br>";
    }
}
