<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;

class Cliente
{
    private $nombre;
    private $numero;
    private $maxAlquilerConcurrente;
    private $numSoportesAlquilados = 0;
    private $soportesAlquilados = [];

    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3)
    {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
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

    public function getMaxAlquilerConcurrente()
    {
        return $this->maxAlquilerConcurrente;
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
            //Lanzamos excepcion
            throw new SoporteYaAlquiladoException("El soporte ya está alquilado por este cliente.");
        }
        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            throw new CupoSuperadoException("No se puede alquilar: se ha superado el maximo de " . $this->maxAlquilerConcurrente . " alquileres.");
        }
        $this->soportesAlquilados[] = $soporte;
        $this->numSoportesAlquilados++;
        //Cambiamos la propiedad alquilado del soporte a true
        $soporte->alquilado = true;
        return $this;
    }

    public function devolver($numSoporte)
    {
        foreach ($this->soportesAlquilados as $indice => $soporte) {
            if ($soporte->getNumero() === $numSoporte) {
                //Cambiamos la propiedad alquilado del soporte a false
                $soporte->alquilado = false;
                unset($this->soportesAlquilados[$indice]);
                $this->soportesAlquilados = array_values($this->soportesAlquilados);
                $this->numSoportesAlquilados--;
                return $this;
            }
        }
        throw new SoporteNoEncontradoException("No se puede devolver: el soporte con número " . $numSoporte . " no está alquilado.");
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
