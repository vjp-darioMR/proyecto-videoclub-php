<?php
require_once "Soporte.php";
require_once "CintaVideo.php";
require_once "Dvd.php";
require_once "Juego.php";
require_once "Cliente.php";

class Videoclub {
    private $nombre;
    private $productos = [];
    private $numProductos = 0;
    private $socios = [];
    private $numSocios = 0;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function incluirCintaVideo($titulo, $precio, $duracion) {
        $cinta = new CintaVideo($titulo, ++$this->numProductos, $precio, $duracion);
        $this->incluirProducto($cinta);
    }

    public function incluirDvd($titulo, $precio, $idiomas, $formatoPantalla) {
        $dvd = new Dvd($titulo, ++$this->numProductos, $precio, $idiomas, $formatoPantalla);
        $this->incluirProducto($dvd);
    }

    public function incluirJuego($titulo, $precio, $consola, $minJugadores, $maxJugadores) {
        $juego = new Juego($titulo, ++$this->numProductos, $precio, $consola, $minJugadores, $maxJugadores);
        $this->incluirProducto($juego);
    }

    private function incluirProducto($producto) {
        $this->productos[] = $producto;
        echo "Producto " . $producto->getTitulo() . " (Número: " . $producto->getNumero() . ") incluido con éxito.<br>";
    }

    public function incluirSocio($nombre, $maxAlquilerConcurrerte = 2) {
        $socio = new Cliente($nombre, ++$this->numSocios, $maxAlquilerConcurrerte);
        $this->socios[] = $socio;
        echo "Socio " . $nombre . " (Número: " . $socio->getNumero() . ") incluido con éxito.<br>";
    }

    public function alquilaSocioProducto($numeroCliente, $numeroSoporte) {
        $socio = $this->buscarSocio($numeroCliente);
        $producto = $this->buscarProducto($numeroSoporte);

        if ($socio && $producto) {
            if ($socio->alquilar($producto)) {
                echo "Alquiler realizado: Socio " . $socio->getNumero() . " ha alquilado " . $producto->getTitulo() . ".<br>";
            }
        } else {
            echo "Error: Socio o producto no encontrado.<br>";
        }
    }

    public function listarProductos() {
        echo "<h3>Lista de Productos</h3>";
        if (count($this->productos) > 0) {
            foreach ($this->productos as $producto) {
                echo "- " . $producto->getTitulo() . " (Número: " . $producto->getNumero() . ", Precio: " . $producto->getPrecio() . " euros)<br>";
            }
        } else {
            echo "No hay productos registrados.<br>";
        }
    }

    public function listarSocios() {
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

    private function buscarSocio($numeroCliente) {
        foreach ($this->socios as $socio) {
            if ($socio->getNumero() === $numeroCliente) {
                return $socio;
            }
        }
        return null;
    }

    private function buscarProducto($numeroSoporte) {
        foreach ($this->productos as $producto) {
            if ($producto->getNumero() === $numeroSoporte) {
                return $producto;
            }
        }
        return null;
    }
}
?>