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

    public $numProductosAlquilados = 0;
    public $numTotalAlquileres = 0;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getNumProductosAlquilados()
    {
        return $this->numProductosAlquilados;
    }

    public function getNumTotalAlquileres()
    {
        return $this->numTotalAlquileres;
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
        
        echo '<div class="alert alert-dismissible alert-success mt-3">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong><i class="bi bi-check2"></i> Producto incluido!</strong> El producto "' . $producto->getTitulo() . '" (Número: ' . $producto->getNumero() . ') ha sido incluido con éxito.
        </div>';
    }

    public function incluirSocio($nombre, $maxAlquilerConcurrerte = 2)
    {
        $socio = new Cliente($nombre, ++$this->numSocios, $maxAlquilerConcurrerte);
        $this->socios[] = $socio;
        echo '<div class="alert alert-dismissible alert-warning mt-3">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong><i class="bi bi-person-check"></i> Socio incluido!</strong> El socio "' . $nombre . '" (Número: ' . $socio->getNumero() . ') ha sido incluido con éxito.
        </div>';
        return $this;
    }

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

    public function alquilarSocioProductos($numSocio, $numerosProductos)
    {
        // Try catch para manejar las excepciones que hemos creado
        try {
            // Buscamos el socio por su número
            $socio = $this->buscarSocio($numSocio);
            // Inicializamos los productos como un array vacío
            $productos = [];
            // Verificamos la disponibilidad de cada producto
            foreach ($numerosProductos as $numProd) {
                // Buscamos el producto por su número
                $prod = $this->buscarProducto($numProd);
                // Verificamos si el producto ya está alquilado
                if ($prod->alquilado) {
                    // Entonces lanzamos la excepción
                    throw new SoporteYaAlquiladoException("El soporte " . $prod->getTitulo() . " ya está alquilado.");
                }
                // Si está disponible, lo añadimos al array de productos a alquilar
                $productos[] = $prod;
            }
            // Verificar si el socio puede alquilar todos
            if ($socio->getNumSoportesAlquilados() + count($productos) > $socio->getMaxAlquilerConcurrerte()) {
                throw new CupoSuperadoException("No se pueden alquilar todos los productos: se superaría el máximo de alquileres.");
            }
            // Todos disponibles, proceder a alquilar
            foreach ($productos as $prod) {
                $socio->alquilar($prod);
            }
            // Después del echo, devuelve this para permitir encadenar llamadas
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
