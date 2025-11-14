<?php

namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\VideoclubException;

// Clase principal que gestiona el videoclub: productos y socios
class Videoclub
{
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
        // Mensaje visual de confirmación
        echo '<div class="alert alert-dismissible alert-success mt-3">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong><i class="bi bi-check2"></i> Producto incluido!</strong> El producto "' . $producto->getTitulo() . '" (Número: ' . $producto->getNumero() . ') ha sido incluido con éxito.
        </div>';
    }

    // Añade un nuevo socio (cliente)
    public function incluirSocio($nombre, $maxAlquilerConcurrente = 3, $username = '', $password = '')
    {
        $numero = ++$this->numSocios;
        $cliente = new Cliente($nombre, $numero, $maxAlquilerConcurrente, $username, $password);

        $this->socios[] = $cliente;

        echo '<div class="alert alert-dismissible alert-warning mt-3">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong><i class="bi bi-person-check"></i> Socio incluido!</strong> El socio "' . $nombre . '" (Número: ' . $numero . ') ha sido incluido con éxito.
    </div>';

        return $this;
    }

    // Alquila un producto a un socio
    public function alquilaSocioProducto($numeroCliente, $numeroSoporte)
    {
        try {
            $socio = $this->buscarSocio($numeroCliente);
            $producto = $this->buscarProducto($numeroSoporte);
            $socio->alquilar($producto);
            echo '<div class="alert alert-dismissible alert-info mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-bag-check"></i> Alquiler realizado!</strong> Socio <span class="badge bg-info text-dark">' . $socio->getNumero() . '</span> ha alquilado <span class="badge bg-secondary">' . $producto->getTitulo() . '</span> con éxito.
            </div>';
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteYaAlquiladoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-x-circle"></i> Ya alquilado:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (CupoSuperadoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-person-x"></i> Cupo superado:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (VideoclubException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error general:</strong> ' . $e->getMessage() . '
            </div>';
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
                    throw new SoporteYaAlquiladoException("El soporte " . $prod->getTitulo() . " ya está alquilado.");
                }
                $productos[] = $prod;
            }
            if ($socio->getNumSoportesAlquilados() + count($productos) > $socio->getMaxAlquilerConcurrerte()) {
                throw new CupoSuperadoException("No se pueden alquilar todos los productos: se superaría el máximo de alquileres.");
            }
            foreach ($productos as $prod) {
                $socio->alquilar($prod);
            }
            echo '<div class="alert alert-dismissible alert-info mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-bag-check"></i> Alquileres realizados!</strong> Socio <span class="badge bg-info text-dark">' . $socio->getNumero() . '</span> ha alquilado <span class="badge bg-secondary">' . count($productos) . ' productos</span> con éxito.
            </div>';
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteYaAlquiladoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-x-circle"></i> Ya alquilado:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (CupoSuperadoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-person-x"></i> Cupo superado:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (VideoclubException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error general:</strong> ' . $e->getMessage() . '
            </div>';
        }
    }

    // Devuelve un producto alquilado por un socio
    public function devolverSocioProducto($numSocio, $numeroProducto)
    {
        try {
            $socio = $this->buscarSocio($numSocio);
            $producto = $this->buscarProducto($numeroProducto);
            $socio->devolver($numeroProducto);
            echo '<div class="alert alert-dismissible alert-success mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-arrow-return-left"></i> Devolución realizada!</strong> Socio <span class="badge bg-success">' . $socio->getNumero() . '</span> ha devuelto <span class="badge bg-secondary">' . $producto->getTitulo() . '</span> con éxito.
            </div>';
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (VideoclubException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error general:</strong> ' . $e->getMessage() . '
            </div>';
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
                    throw new SoporteNoEncontradoException("El soporte " . $prod->getTitulo() . " no está alquilado por este socio.");
                }
                $productos[] = $prod;
            }
            foreach ($productos as $prod) {
                $socio->devolver($prod->getNumero());
            }
            echo '<div class="alert alert-dismissible alert-success mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-arrow-return-left"></i> Devoluciones realizadas!</strong> Socio <span class="badge bg-success">' . $socio->getNumero() . '</span> ha devuelto <span class="badge bg-secondary">' . count($productos) . ' productos</span> con éxito.
            </div>';
            return $this;
        } catch (ClienteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (SoporteNoEncontradoException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong> ' . $e->getMessage() . '
            </div>';
        } catch (VideoclubException $e) {
            echo '<div class="alert alert-dismissible alert-danger mt-3">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong><i class="bi bi-exclamation-triangle"></i> Error general:</strong> ' . $e->getMessage() . '
            </div>';
        }
    }

    // Lista todos los productos del videoclub
    public function listarProductos()
    {
        if (count($this->productos) > 0) {
            foreach ($this->productos as $producto) {
                echo '<div class="col">
                        <div class="card border-primary mb-3 mx-2" style="max-width: 20rem;">
                            <div class="card-header">' . $producto->getTitulo() . ' <span class="badge rounded-pill bg-primary">' . $producto->getNumero() . '</span></div>
                            <div class="card-body">
                                <h4 class="card-title text-center">Precio: ' . $producto->getPrecio() . ' euros</h4>
                                <button type="button" class="btn btn-outline-success w-100">Alquilar</button>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo "No hay productos registrados.<br>";
        }
    }

    // Lista todos los socios del videoclub
    public function listarSocios()
    {
        if (count($this->socios) > 0) {
            foreach ($this->socios as $socio) {
                echo '<div class="col">
                        <div class="card border-warning mb-3 mx-2" style="max-width: 20rem;">
                            <div class="card-header">' . $socio->getNombre() . ' <span class="badge rounded-pill bg-warning text-dark">' . $socio->getNumero() . '</span></div>
                            <div class="card-body">
                                <h4 class="card-title text-center">Alquileres: ' . $socio->getNumSoportesAlquilados() . '</h4>
                                <button type="button" class="btn btn-outline-primary w-100">Ver detalles</button>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo "No hay socios registrados.<br>";
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
        throw new SoporteNoEncontradoException("Producto con número " . $numeroSoporte . " no encontrado.");
    }
    public function getSocios()
    {
        return $this->socios;
    }
    public function getProductos()
    {
        return $this->productos;
    }
}
