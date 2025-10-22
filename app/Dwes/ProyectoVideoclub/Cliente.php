<?php
namespace Dwes\ProyectoVideoclub;
require_once "Soporte.php";

use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;

class Cliente
{
    // Propiedades principales del cliente
    private $nombre;
    private $numero;
    private $maxAlquilerConcurrente;
    private $numSoportesAlquilados = 0;
    private $soportesAlquilados = [];

    // Constructor: inicializa el cliente
    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3)
    {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
    }

    // Métodos para obtener información básica del cliente
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

    // Comprueba si el cliente ya tiene alquilado un soporte
    public function tieneAlquilado($soporte)
    {
        foreach ($this->soportesAlquilados as $alquilado) {
            if ($alquilado === $soporte) {
                echo '<div class="alert alert-dismissible alert-danger mt-3">'
                    . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>'
                    . '<strong><i class="bi bi-exclamation-triangle"></i> Soporte ya alquilado!</strong> El soporte "' . $soporte->getTitulo() . '" ya está alquilado por este cliente.'
                    . '</div>';
                return true;
            }
        }
        echo '<div class="alert alert-dismissible alert-info mt-3">'
            . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>'
            . '<strong><i class="bi bi-info-circle"></i> Soporte no alquilado!</strong> El soporte "' . $soporte->getTitulo() . '" no está alquilado por este cliente.'
            . '</div>';
        return false;
    }

    // Alquila un soporte si no supera el cupo y no está ya alquilado
    public function alquilar(Soporte $soporte)
    {
        if ($this->tieneAlquilado($soporte)) {
            throw new SoporteYaAlquiladoException("El soporte ya está alquilado por este cliente.");
        }
        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            throw new CupoSuperadoException("No se puede alquilar: se ha superado el maximo de " . $this->maxAlquilerConcurrente . " alquileres.");
        }
        $this->soportesAlquilados[] = $soporte;
        $this->numSoportesAlquilados++;
        $soporte->alquilado = true;
        return $this;
    }

    // Devuelve un soporte alquilado por el cliente
    public function devolver($numSoporte)
    {
        foreach ($this->soportesAlquilados as $indice => $soporte) {
            if ($soporte->getNumero() === $numSoporte) {
                $soporte->alquilado = false;
                unset($this->soportesAlquilados[$indice]);
                $this->soportesAlquilados = array_values($this->soportesAlquilados);
                $this->numSoportesAlquilados--;
                return $this;
            }
        }
        throw new SoporteNoEncontradoException("No se puede devolver: el soporte con número " . $numSoporte . " no está alquilado.");
    }

    // Muestra los alquileres actuales del cliente
    public function listarAlquileres()
    {
        echo '<h2 class="mt-4 mb-4"><i class="bi bi-bag"></i> Alquileres: ' . $this->numSoportesAlquilados . '</h2>';
        if ($this->numSoportesAlquilados > 0) {
            echo '<div class="row row-cols-1 row-cols-md-3 g-3">';
            foreach ($this->soportesAlquilados as $soporte) {
                echo '<div class="col">
                        <div class="card border-success mb-3 mx-2" style="max-width: 20rem;">
                            <div class="card-header">' . $soporte->getTitulo() . ' <span class="badge rounded-pill bg-success">' . $soporte->getNumero() . '</span></div>
                            <div class="card-body text-center">
                                <h4 class="card-title text-center">Precio: ' . $soporte->getPrecio() . ' €</h4>
                                <span class="badge bg-success ">Alquilado</span>
                            </div>
                        </div>
                    </div>';
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">No hay alquileres registrados.</div>';
        }
    }

    // Muestra un resumen básico del cliente
    public function muestraResumen()
    {
        echo "Nombre: " . $this->nombre . "<br>";
        echo "Alquileres realizados: " . $this->numSoportesAlquilados . "<br>";
    }
}
