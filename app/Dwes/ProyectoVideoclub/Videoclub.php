<?php
namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\VideoclubException;

class Videoclub
{
    private $nombre;
    private $productos = [];
    private $numProductos = 0;
    private $socios = [];
    private $numSocios = 0;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function incluirCintaVideo($titulo, $precio, $duracion)
    {
        $cinta = new CintaVideo($titulo, ++$this->numProductos, $precio, $duracion);
        $this->incluirProducto($cinta);
        return $this;
    }

    public function incluirDvd($titulo, $precio, $idiomas, $formatoPantalla)
    {
        $dvd = new Dvd($titulo, ++$this->numProductos, $precio, $idiomas, $formatoPantalla);
        $this->incluirProducto($dvd);
        return $this;
    }

    public function incluirJuego($titulo, $precio, $consola, $minJugadores, $maxJugadores)
    {
        $juego = new Juego($titulo, ++$this->numProductos, $precio, $consola, $minJugadores, $maxJugadores);
        $this->incluirProducto($juego);
        return $this;
    }

    private function incluirProducto($producto)
    {
        $this->productos[] = $producto;
        echo "Producto " . $producto->getTitulo() . " (Número: " . $producto->getNumero() . ") incluido con éxito.<br>";
    }

    public function incluirSocio($nombre, $maxAlquilerConcurrerte = 2)
    {
        $socio = new Cliente($nombre, ++$this->numSocios, $maxAlquilerConcurrerte);
        $this->socios[] = $socio;
        echo "Socio " . $nombre . " (Número: " . $socio->getNumero() . ") incluido con éxito.<br>";
        return $this;
    }

    public function alquilaSocioProducto($numeroCliente, $numeroSoporte)
    {
        try {
            $socio = $this->buscarSocio($numeroCliente);
            $producto = $this->buscarProducto($numeroSoporte);
            $socio->alquilar($producto);
            echo "Alquiler realizado: Socio " . $socio->getNumero() . " ha alquilado " . $producto->getTitulo() . ".<br>";
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        } catch (SoporteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        } catch (SoporteYaAlquiladoException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        } catch (CupoSuperadoException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        } catch (VideoclubException $e) {
            echo "Error general: " . $e->getMessage() . "<br>";
        }
    }

    public function listarProductos()
    {
        echo "<h3>Lista de Productos</h3>";
        if (count($this->productos) > 0) {
            foreach ($this->productos as $producto) {
                echo "- " . $producto->getTitulo() . " (Número: " . $producto->getNumero() . ", Precio: " . $producto->getPrecio() . " euros)<br>";
            }
        } else {
            echo "No hay productos registrados.<br>";
        }
    }

    public function listarSocios()
    {
        echo "<h3>Lista de Socios</h3>";
        if (count($this->socios) > 0) {
            foreach ($this->socios as $socio) {
                echo "- " . $socio->getNombre() . " (Número: " . $socio->getNumero() . ", Alquileres: " . $socio->getNumSoportesAlquilados() . ")<br>";
                $socio->listarAlquileres();
            }
        } else {
            echo "No hay socios registrados.<br>";
        }
    }

    private function buscarSocio($numeroCliente)
    {
        foreach ($this->socios as $socio) {
            if ($socio->getNumero() === $numeroCliente) {
                return $socio;
            }
        }
        throw new ClienteNoEncontradoException("Socio con número " . $numeroCliente . " no encontrado.");
    }

    private function buscarProducto($numeroSoporte)
    {
        foreach ($this->productos as $producto) {
            if ($producto->getNumero() === $numeroSoporte) {
                return $producto;
            }
        }
        throw new SoporteNoEncontradoException("Producto con número " . $numeroSoporte . " no encontrado.");
    }
}
