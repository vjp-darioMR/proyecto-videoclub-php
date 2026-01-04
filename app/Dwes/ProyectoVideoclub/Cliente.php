<?php

namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Psr\Log\LoggerInterface;
use Dwes\ProyectoVideoclub\Util\LogFactory;

class Cliente
{
    // Propiedades principales del cliente
    private $nombre;
    private $numero;
    private $maxAlquilerConcurrente;
    private $numSoportesAlquilados = 0;
    private $username;
    private $password;
    private $soportesAlquilados = [];
    private $logger;

    // Constructor: inicializa el cliente
    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3, $username = '', $password = '')
    {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
        $this->username = $username;
        $this->password = $password;

        // Inicializar logger: canal VideoclubLogger, fichero logs/videoclub.log
        $this->logger = LogFactory::createLogger('VideoclubLogger');
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
    public function getAlquileres(): array
    {
        return $this->soportesAlquilados;
    }

    // Comprueba si el cliente ya tiene alquilado un soporte
    public function tieneAlquilado($soporte)
    {
        foreach ($this->soportesAlquilados as $alquilado) {
            if ($alquilado === $soporte) {
                $this->logger->info('Soporte ya alquilado: ' . $soporte->getTitulo(), ['soporte' => $soporte->getNumero()]);
                return true;
            }
        }
        $this->logger->info('Soporte no alquilado: ' . $soporte->getTitulo(), ['soporte' => $soporte->getNumero()]);
        return false;
    }

    // Alquila un soporte si no supera el cupo y no está ya alquilado
    public function alquilar(Soporte $soporte)
    {
        if ($this->tieneAlquilado($soporte)) {
            $this->logger->warning('Intento de alquiler de soporte ya alquilado: ' . $soporte->getTitulo(), ['soporte' => $soporte->getNumero(), 'cliente' => $this->numero]);
            throw new SoporteYaAlquiladoException("El soporte ya está alquilado por este cliente.");
        }
        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            $this->logger->warning('Cupo superado al intentar alquilar: max ' . $this->maxAlquilerConcurrente, ['cliente' => $this->numero]);
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
        $this->logger->warning('Intento de devolver soporte no alquilado: ' . $numSoporte, ['cliente' => $this->numero]);
        throw new SoporteNoEncontradoException("No se puede devolver: el soporte con número " . $numSoporte . " no está alquilado.");
    }

    // Muestra los alquileres actuales del cliente
    public function listarAlquileres()
    {
        $this->logger->info('Alquileres actuales: ' . $this->numSoportesAlquilados, ['cliente' => $this->numero]);
        if ($this->numSoportesAlquilados > 0) {
            foreach ($this->soportesAlquilados as $soporte) {
                $this->logger->info('Soporte alquilado: ' . $soporte->getTitulo(), ['soporte' => $soporte->getNumero(), 'precio' => $soporte->getPrecio()]);
            }
        } else {
            $this->logger->info('No hay alquileres registrados para el cliente.', ['cliente' => $this->numero]);
        }
    }

    // Muestra un resumen básico del cliente
    public function muestraResumen()
    {
        echo "Nombre: " . $this->nombre . "<br>";
        echo "Alquileres realizados: " . $this->numSoportesAlquilados . "<br>";
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }

    // Setters para edición de datos
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setMaxAlquilerConcurrente($maxAlquiler)
    {
        $this->maxAlquilerConcurrente = $maxAlquiler;
        return $this;
    }
}
