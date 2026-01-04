<?php

namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\VideoclubException;
use Monolog\Logger;
use Dwes\ProyectoVideoclub\Util\LogFactory;

// Clase principal que gestiona el videoclub: productos y socios
class Videoclub
{
    private $logger;
    private $nombre;
    private $productos = [];
    private $numProductos = 0;
    private $socios = [];
    private $numSocios = 0;

    public $numProductosAlquilados = 0;
    public $numTotalAlquileres = 0;

    // Constructor: inicializa el videoclub con un nombre
    public function __construct($nombre)
    {
        $this->nombre = $nombre;
        // Inicializar logger: canal VideoclubLogger, fichero logs/videoclub.log
        $this->logger = LogFactory::createLogger('VideoclubLogger');
    }

    // Métodos para obtener estadísticas de alquileres
    public function getNumProductosAlquilados()
    {
        return $this->numProductosAlquilados;
    }

    public function getNumTotalAlquileres()
    {
        return $this->numTotalAlquileres;
    }

    // Métodos para incluir productos al videoclub
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

    // Añade un producto al array de productos
    private function incluirProducto($producto)
    {
        $this->productos[] = $producto;
        // Log de confirmación
        $this->logger->info('Producto incluido: ' . $producto->getTitulo(), ['producto' => $producto->getNumero(), 'titulo' => $producto->getTitulo(), 'precio' => $producto->getPrecio()]);
    }

    // Añade un nuevo socio (cliente)
    public function incluirSocio($nombre, $maxAlquilerConcurrente = 3, $username = '', $password = '')
    {
        $numero = ++$this->numSocios;
        $cliente = new Cliente($nombre, $numero, $maxAlquilerConcurrente, $username, $password);

        $this->socios[] = $cliente;

        $this->logger->info('Socio incluido: ' . $nombre, ['cliente' => $numero, 'nombre' => $nombre, 'username' => $username]);

        return $this;
    }

    // Alquila un producto a un socio
    public function alquilaSocioProducto($numeroCliente, $numeroSoporte)
    {
        try {
            $socio = $this->buscarSocio($numeroCliente);
            $producto = $this->buscarProducto($numeroSoporte);
            $socio->alquilar($producto);
            $this->logger->info('Alquiler realizado', ['cliente' => $socio->getNumero(), 'producto' => $producto->getNumero(), 'titulo' => $producto->getTitulo()]);
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            $this->logger->info('Error cliente no encontrado al alquilar', ['cliente' => $numeroCliente, 'error' => $e->getMessage()]);
        } catch (SoporteNoEncontradoException $e) {
            $this->logger->info('Error soporte no encontrado al alquilar', ['soporte' => $numeroSoporte, 'error' => $e->getMessage()]);
        } catch (SoporteYaAlquiladoException $e) {
            $this->logger->info('Soporte ya alquilado al intentar alquilar', ['soporte' => $numeroSoporte, 'cliente' => $numeroCliente, 'error' => $e->getMessage()]);
        } catch (CupoSuperadoException $e) {
            $this->logger->info('Cupo superado al intentar alquilar', ['cliente' => $numeroCliente, 'error' => $e->getMessage()]);
        } catch (VideoclubException $e) {
            $this->logger->info('Error general al alquilar', ['error' => $e->getMessage()]);
        }
    }

    // Alquila varios productos a un socio
    public function alquilarSocioProductos($numSocio, $numerosProductos)
    {
        try {
            $socio = $this->buscarSocio($numSocio);
            $productos = [];
            foreach ($numerosProductos as $numProd) {
                $prod = $this->buscarProducto($numProd);
                if ($prod->alquilado) {
                        $this->logger->warning('Intento de alquiler de soporte ya alquilado', ['soporte' => $prod->getNumero(), 'titulo' => $prod->getTitulo(), 'cliente' => $numSocio]);
                        throw new SoporteYaAlquiladoException("El soporte " . $prod->getTitulo() . " ya está alquilado.");
                }
                $productos[] = $prod;
            }
                if ($socio->getNumSoportesAlquilados() + count($productos) > $socio->getMaxAlquilerConcurrerte()) {
                    $this->logger->warning('Cupo superado al intentar alquilar varios productos', ['cliente' => $socio->getNumero(), 'intentados' => count($productos), 'maximo' => $socio->getMaxAlquilerConcurrerte()]);
                    throw new CupoSuperadoException("No se pueden alquilar todos los productos: se superaría el máximo de alquileres.");
                }
            foreach ($productos as $prod) {
                $socio->alquilar($prod);
            }
            $this->logger->info('Alquileres realizados', ['cliente' => $socio->getNumero(), 'cantidad' => count($productos), 'productos' => $numerosProductos]);
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            $this->logger->warning('Error cliente no encontrado al alquilar varios', ['cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (SoporteNoEncontradoException $e) {
            $this->logger->warning('Error soporte no encontrado al alquilar varios', ['soportes' => $numerosProductos, 'cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (SoporteYaAlquiladoException $e) {
            $this->logger->warning('Soporte ya alquilado al intentar alquilar varios', ['soportes' => $numerosProductos, 'cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (CupoSuperadoException $e) {
            $this->logger->warning('Cupo superado al alquilar varios productos', ['cliente' => $numSocio, 'cantidad_solicitada' => count($numerosProductos), 'error' => $e->getMessage()]);
        } catch (VideoclubException $e) {
            $this->logger->warning('Error general al alquilar varios', ['cliente' => $numSocio, 'error' => $e->getMessage()]);
        }
    }

    // Devuelve un producto alquilado por un socio
    public function devolverSocioProducto($numSocio, $numeroProducto)
    {
        try {
            $socio = $this->buscarSocio($numSocio);
            $producto = $this->buscarProducto($numeroProducto);
            $socio->devolver($numeroProducto);
            $this->logger->info('Devolución realizada', ['cliente' => $socio->getNumero(), 'producto' => $producto->getNumero(), 'titulo' => $producto->getTitulo()]);
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            $this->logger->info('Error cliente no encontrado al devolver', ['cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (SoporteNoEncontradoException $e) {
            $this->logger->info('Error soporte no encontrado al devolver', ['soporte' => $numeroProducto, 'error' => $e->getMessage()]);
        } catch (VideoclubException $e) {
            $this->logger->info('Error general al devolver', ['error' => $e->getMessage()]);
        }
    }

    // Devuelve varios productos alquilados por un socio
    public function devolverSocioProductos($numSocio, $numerosProductos)
    {
        try {
            $socio = $this->buscarSocio($numSocio);
            $productos = [];
            foreach ($numerosProductos as $numProd) {
                $prod = $this->buscarProducto($numProd);
                if (!$socio->tieneAlquilado($prod)) {
                    $this->logger->warning('Intento de devolver soporte no alquilado por socio', ['producto' => $prod->getNumero(), 'titulo' => $prod->getTitulo(), 'cliente' => $numSocio]);
                    throw new SoporteNoEncontradoException("El soporte " . $prod->getTitulo() . " no está alquilado por este socio.");
                }
                $productos[] = $prod;
            }
            foreach ($productos as $prod) {
                $socio->devolver($prod->getNumero());
            }
            $this->logger->info('Devoluciones realizadas', ['cliente' => $socio->getNumero(), 'cantidad' => count($productos), 'productos' => $numerosProductos]);
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            $this->logger->warning('Error cliente no encontrado al devolver varios', ['cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (SoporteNoEncontradoException $e) {
            $this->logger->warning('Error soporte no encontrado al devolver varios', ['soportes' => $numerosProductos, 'cliente' => $numSocio, 'error' => $e->getMessage()]);
        } catch (VideoclubException $e) {
            $this->logger->warning('Error general al devolver varios', ['cliente' => $numSocio, 'error' => $e->getMessage()]);
        }
    }

    // Lista todos los productos del videoclub
    public function listarProductos()
    {
        if (count($this->productos) > 0) {
            foreach ($this->productos as $producto) {
                $this->logger->info('Producto listado', ['producto' => $producto->getNumero(), 'titulo' => $producto->getTitulo(), 'precio' => $producto->getPrecio(), 'alquilado' => $producto->alquilado]);
            }
        } else {
            $this->logger->info('No hay productos registrados', ['videoclub' => $this->nombre]);
        }
    }

    // Lista todos los socios del videoclub
    public function listarSocios()
    {
        if (count($this->socios) > 0) {
            foreach ($this->socios as $socio) {
                $this->logger->info('Socio listado', ['cliente' => $socio->getNumero(), 'nombre' => $socio->getNombre(), 'alquileres' => $socio->getNumSoportesAlquilados(), 'maximo' => $socio->getMaxAlquilerConcurrente()]);
            }
        } else {
            $this->logger->info('No hay socios registrados', ['videoclub' => $this->nombre]);
        }
    }

    // Busca un socio por su número
    private function buscarSocio($numeroCliente)
    {
        foreach ($this->socios as $socio) {
            if ($socio->getNumero() === $numeroCliente) {
                return $socio;
            }
        }
        if (isset($this->logger)) {
            $this->logger->warning('Socio no encontrado', ['cliente' => $numeroCliente]);
        }
        throw new ClienteNoEncontradoException("Socio con número " . $numeroCliente . " no encontrado.");
    }

    // Busca un producto por su número
    private function buscarProducto($numeroSoporte)
    {
        foreach ($this->productos as $producto) {
            if ($producto->getNumero() === $numeroSoporte) {
                return $producto;
            }
        }
        if (isset($this->logger)) {
            $this->logger->warning('Producto no encontrado', ['producto' => $numeroSoporte]);
        }
        throw new SoporteNoEncontradoException("Producto con número " . $numeroSoporte . " no encontrado.");
    }
    public function getSocios()
    {
        return $this->socios;
    }

    public function setSocios($socios)
    {
        $this->socios = $socios;
        // Reiniciar el contador de socios al número de socios existentes
        $this->numSocios = count($socios);
        return $this;
    }

    public function getProductos()
    {
        return $this->productos;
    }
}
