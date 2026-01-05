<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\Videoclub;
use Dwes\ProyectoVideoclub\CintaVideo;
use Dwes\ProyectoVideoclub\Dvd;
use Dwes\ProyectoVideoclub\Juego;
use Dwes\ProyectoVideoclub\Exception\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Exception\ClienteNoExisteException;
use Dwes\ProyectoVideoclub\Exception\SoporteNoEncontradoException;
use Dwes\ProyectoVideoclub\Exception\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Exception\CupoSuperadoException;

class VideoclubTest extends TestCase
{
    private $videoclub;

    protected function setUp(): void
    {
        $this->videoclub = new Videoclub('Test Videoclub');
    }

    /**
     * @test
     * Prueba creación básica del videoclub
     */
    public function testCreacionVideoclub()
    {
        $this->assertInstanceOf(Videoclub::class, $this->videoclub);
        $this->assertEquals(0, $this->videoclub->getNumProductosAlquilados());
        $this->assertEquals(0, $this->videoclub->getNumTotalAlquileres());
    }

    /**
     * @test
     * Prueba incluir cintas de video
     */
    public function testIncluirCintasVideo()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        
        $productos = $this->videoclub->getProductos();
        $this->assertCount(2, $productos);
        $this->assertEquals('Avatar', $productos[0]->getTitulo());
        $this->assertEquals('Titanic', $productos[1]->getTitulo());
    }

    /**
     * @test
     * Prueba incluir DVDs
     */
    public function testIncluirDvds()
    {
        $this->videoclub->incluirDvd('Inception', 4.5, ['ES', 'EN'], '16:9', 148);
        $this->videoclub->incluirDvd('The Matrix', 3.0, ['ES', 'EN', 'FR'], '16:9', 136);
        
        $productos = $this->videoclub->getProductos();
        $this->assertCount(2, $productos);
        $this->assertEquals('Inception', $productos[0]->getTitulo());
    }

    /**
     * @test
     * Prueba incluir juegos
     */
    public function testIncluirJuegos()
    {
        $this->videoclub->incluirJuego('Elden Ring', 59.99, 'PS5', 1, 4);
        $this->videoclub->incluirJuego('God of War', 49.99, 'PS5', 1, 1);
        
        $productos = $this->videoclub->getProductos();
        $this->assertCount(2, $productos);
        $this->assertEquals('Elden Ring', $productos[0]->getTitulo());
    }

    /**
     * @test
     * Prueba incluir socios (clientes)
     */
    public function testIncluirSocios()
    {
        $this->videoclub->incluirSocio('Juan', 2, 'juan', 'pass123');
        $this->videoclub->incluirSocio('Maria', 3, 'maria', 'pass456');
        
        $socios = $this->videoclub->getSocios();
        $this->assertCount(2, $socios);
        $this->assertEquals('Juan', $socios[0]->getNombre());
        $this->assertEquals('Maria', $socios[1]->getNombre());
        $this->assertEquals('juan', $socios[0]->getUsername());
    }

    /**
     * @test
     * Prueba fluent interface en incluirSocio
     */
    public function testIncluirSocioFluentInterface()
    {
        $resultado = $this->videoclub->incluirSocio('Test', 2);
        $this->assertInstanceOf(Videoclub::class, $resultado);
        $this->assertEquals($this->videoclub, $resultado);
    }

    /**
     * @test
     * Prueba alquiler individual de soporte
     */
    public function testAlquilerIndividualExitoso()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirSocio('Juan', 2);
        
        $this->videoclub->alquilaSocioProducto(1, 1);
        
        $socios = $this->videoclub->getSocios();
        $this->assertEquals(1, $socios[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler individual con cliente no encontrado
     */
    public function testAlquilerIndividualClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        
        // No lanza excepción, solo la captura internamente
        $this->videoclub->alquilaSocioProducto(999, 1);
        
        $socios = $this->videoclub->getSocios();
        $this->assertCount(0, $socios);
    }

    /**
     * @test
     * Prueba alquiler individual con soporte no encontrado
     */
    public function testAlquilerIndividualSoporteNoEncontrado()
    {
        $this->videoclub->incluirSocio('Juan', 2);
        
        // No lanza excepción, solo la captura internamente
        $this->videoclub->alquilaSocioProducto(1, 999);
        
        $socios = $this->videoclub->getSocios();
        $this->assertEquals(0, $socios[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba devolución individual de soporte
     */
    public function testDevolucionIndividualExitosa()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirSocio('Juan', 2);
        
        $this->videoclub->alquilaSocioProducto(1, 1);
        $this->assertEquals(1, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        $this->videoclub->devolverSocioProducto(1, 1);
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple de soportes
     */
    public function testAlquilerMultipleExitoso()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirSocio('Juan', 3);
        
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        
        $socios = $this->videoclub->getSocios();
        $this->assertEquals(3, $socios[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple cuando uno ya está alquilado
     */
    public function testAlquilerMultipleConSoporteYaAlquilado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->incluirSocio('Maria', 3);
        
        // Maria alquila Avatar
        $this->videoclub->alquilaSocioProducto(2, 1);
        
        // Juan intenta alquilar Avatar, Titanic y Jaws pero Avatar ya está alquilado
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        
        // Juan no debe tener ninguno alquilado (la transacción falló)
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple que supera cupo
     */
    public function testAlquilerMultipleSuperandoCupo()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirCintaVideo('The Ring', 1.5, 115);
        $this->videoclub->incluirSocio('Juan', 2); // Cupo máximo de 2
        
        // Intenta alquilar 3 soportes con cupo de 2
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        
        // No debe alquilar ninguno (la transacción falló)
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba devolución múltiple de soportes
     */
    public function testDevolucionMultipleExitosa()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirSocio('Juan', 3);
        
        // Alquila 3 soportes
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        $this->assertEquals(3, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Devuelve 2
        $this->videoclub->devolverSocioProductos(1, [1, 2]);
        $this->assertEquals(1, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba devolución múltiple con soporte no alquilado
     */
    public function testDevolucionMultipleConSoporteNoAlquilado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirSocio('Juan', 3);
        
        // Alquila 2 soportes
        $this->videoclub->alquilarSocioProductos(1, [1, 2]);
        $this->assertEquals(2, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Intenta devolver 3 (Jaws no está alquilado)
        $this->videoclub->devolverSocioProductos(1, [1, 2, 3]);
        
        // Debe mantener los 2 alquilados (la transacción falló)
        $this->assertEquals(2, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * Proveedor de datos para diferentes cantidades de productos
     */
    public static function proveedorCantidadesProductos(): array
    {
        return [
            '5 productos' => [5],
            '10 productos' => [10],
            '15 productos' => [15],
            '20 productos' => [20],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorCantidadesProductos
     * Prueba incluir diferentes cantidades de productos
     */
    public function testIncluirMuchosProductos($cantidad)
    {
        $videoclub = new Videoclub('Test Muchos');
        
        for ($i = 0; $i < $cantidad; $i++) {
            $videoclub->incluirCintaVideo("Película $i", 2.5 + $i * 0.5, 100 + $i * 10);
        }
        
        $this->assertCount($cantidad, $videoclub->getProductos());
    }

    /**
     * Proveedor de datos para diferentes cantidades de socios
     */
    public static function proveedorCantidadesSocios(): array
    {
        return [
            '3 socios' => [3],
            '5 socios' => [5],
            '10 socios' => [10],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorCantidadesSocios
     * Prueba incluir diferentes cantidades de socios
     */
    public function testIncluirMuchosSocios($cantidad)
    {
        $videoclub = new Videoclub('Test Socios');
        
        for ($i = 0; $i < $cantidad; $i++) {
            $videoclub->incluirSocio("Socio $i", 3, "user$i", "pass$i");
        }
        
        $this->assertCount($cantidad, $videoclub->getSocios());
    }

    /**
     * Proveedor de datos para diferentes tipos de alquileres
     */
    public static function proveedorTiposAlquileres(): array
    {
        return [
            'solo cintas' => ['cinta', 3],
            'solo dvds' => ['dvd', 3],
            'solo juegos' => ['juego', 2],
            'mezcla' => ['mix', 2],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorTiposAlquileres
     * Prueba alquileres de diferentes tipos de soportes
     */
    public function testAlquileresConDiferentesTipos($tipo, $cupo)
    {
        $videoclub = new Videoclub('Test Tipos');
        $videoclub->incluirSocio('Cliente', $cupo);
        
        if ($tipo === 'cinta' || $tipo === 'mix') {
            $videoclub->incluirCintaVideo('Avatar', 3.5, 120);
            $videoclub->alquilaSocioProducto(1, 1);
        }
        if ($tipo === 'dvd' || $tipo === 'mix') {
            $videoclub->incluirDvd('Inception', 4.5, ['ES', 'EN'], '16:9', 148);
            $videoclub->alquilaSocioProducto(1, $tipo === 'mix' ? 2 : 1);
        }
        if ($tipo === 'juego') {
            $videoclub->incluirJuego('Elden Ring', 59.99, 'PS5', 1, 4);
            $videoclub->alquilaSocioProducto(1, 1);
        }
        
        $this->assertGreaterThan(0, $videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba getSocios y setSocios
     */
    public function testGetSetSocios()
    {
        $this->videoclub->incluirSocio('Juan', 2);
        $this->videoclub->incluirSocio('Maria', 3);
        
        $socios = $this->videoclub->getSocios();
        $this->assertCount(2, $socios);
        
        // Modifica los socios
        $nuevosSocios = array_slice($socios, 0, 1);
        $this->videoclub->setSocios($nuevosSocios);
        
        $this->assertCount(1, $this->videoclub->getSocios());
    }

    /**
     * @test
     * Prueba que fluent interface funciona en métodos principales
     */
    public function testFluentInterface()
    {
        $resultado = $this->videoclub
            ->incluirCintaVideo('Avatar', 3.5, 120)
            ->incluirSocio('Juan', 2)
            ->alquilaSocioProducto(1, 1)
            ->devolverSocioProducto(1, 1);
        
        $this->assertInstanceOf(Videoclub::class, $resultado);
    }

    /**
     * @test
     * Prueba transaccionalidad en alquiler múltiple (atomicidad)
     */
    public function testTransaccionalidadAlquilerMultiple()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Jaws', 2.0, 124);
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->incluirSocio('Maria', 3);
        
        // Maria alquila Avatar
        $this->videoclub->alquilaSocioProducto(2, 1);
        
        // Juan intenta alquilar [Avatar, Titanic, Jaws] pero Avatar ya está alquilado
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        
        // Verificar que Juan no alquiló nada (transacción atómica)
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Verificar que el avatar sigue alquilado por María
        $this->assertTrue($this->videoclub->getProductos()[0]->alquilado);
    }

    /**
     * @test
     * Prueba búsqueda de socio no existente en alquiler
     */
    public function testAlquilerConSocioNoExistente()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        
        // Intenta alquilar a socio que no existe
        $this->videoclub->alquilaSocioProducto(999, 1);
        
        // No debe haber excepción, solo capturada internamente
        $this->assertCount(0, $this->videoclub->getSocios());
    }

    /**
     * @test
     * Prueba devolución con cliente no encontrado
     */
    public function testDevolucionConClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        
        // Intenta devolver para cliente que no existe
        $this->videoclub->devolverSocioProducto(999, 1);
        
        // No debe lanzar excepción
        $this->assertCount(0, $this->videoclub->getSocios());
    }

    /**
     * @test
     * Prueba ciclo completo: añadir, alquilar, devolver con múltiples soportes
     */
    public function testCicloCompletoConMultiplesSoportes()
    {
        // Añadir productos
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirDvd('Inception', 4.5, ['ES', 'EN'], '16:9', 148);
        $this->videoclub->incluirJuego('Elden Ring', 59.99, 'PS5', 1, 4);
        
        // Añadir socios
        $this->videoclub->incluirSocio('Juan', 3);
        
        // Alquilar múltiple
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        $this->assertEquals(3, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Devolver parcial
        $this->videoclub->devolverSocioProductos(1, [1, 2]);
        $this->assertEquals(1, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Alquilar más
        $this->videoclub->alquilaSocioProducto(1, 1);
        $this->assertEquals(2, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Devolver todo
        $this->videoclub->devolverSocioProductos(1, [1, 3]);
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler de producto a cliente no encontrado
     */
    public function testAlquilaSocioProductoClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->alquilaSocioProducto(999, 1);
        // No debe lanzar excepción (está capturada), simplemente no hacer nada
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba alquiler de producto no encontrado
     */
    public function testAlquilaSocioProductoProductoNoEncontrado()
    {
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->alquilaSocioProducto(1, 999);
        // No debe lanzar excepción (está capturada), simplemente no hacer nada
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba alquiler de producto ya alquilado
     */
    public function testAlquilaSocioProductoYaAlquilado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->incluirSocio('Maria', 3);
        
        // Primer alquiler exitoso
        $this->videoclub->alquilaSocioProducto(1, 1);
        $this->assertTrue($this->videoclub->getProductos()[0]->alquilado);
        
        // Segundo alquiler del mismo producto a otro cliente
        $this->videoclub->alquilaSocioProducto(2, 1);
        // No debe lanzar excepción (está capturada), simplemente no hacer nada
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba alquiler superando cupo de un socio
     */
    public function testAlquilaSocioProductoCupoSuperado()
    {
        // Crear 3 productos
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Inception', 4.5, 148);
        
        // Crear socio con cupo 2
        $this->videoclub->incluirSocio('Juan', 2);
        
        // Alquilar hasta el límite
        $this->videoclub->alquilaSocioProducto(1, 1);
        $this->videoclub->alquilaSocioProducto(1, 2);
        $this->assertEquals(2, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
        
        // Intentar exceder el cupo
        $this->videoclub->alquilaSocioProducto(1, 3);
        // No debe lanzar excepción (está capturada), seguir con 2 alquileres
        $this->assertEquals(2, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple con cliente no encontrado
     */
    public function testAlquilarSocioProductosClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        
        // Debe lanzar ClienteNoExisteException cuando el cliente no existe
        $this->expectException(ClienteNoExisteException::class);
        $this->videoclub->alquilarSocioProductos(999, [1, 2]);
    }

    /**
     * @test
     * Prueba alquiler múltiple con producto no encontrado
     */
    public function testAlquilarSocioProductosSoporteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->alquilarSocioProductos(1, [1, 999]);
        // No debe lanzar excepción (está capturada), socio sin alquileres
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple con soporte ya alquilado
     */
    public function testAlquilarSocioProductosSoporteYaAlquilado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->incluirSocio('Maria', 3);
        
        // Alquilar Avatar a Juan
        $this->videoclub->alquilaSocioProducto(1, 1);
        
        // Intentar alquilar Avatar a Maria en lote
        $this->videoclub->alquilarSocioProductos(2, [1, 2]);
        // No debe lanzar excepción (está capturada), Maria sin alquileres
        $this->assertEquals(0, $this->videoclub->getSocios()[1]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler múltiple superando cupo
     */
    public function testAlquilarSocioProductosCupoSuperado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirCintaVideo('Inception', 4.5, 148);
        $this->videoclub->incluirCintaVideo('The Matrix', 3.0, 136);
        
        // Crear socio con cupo 2
        $this->videoclub->incluirSocio('Juan', 2);
        
        // Intentar alquilar 3 productos en lote
        $this->videoclub->alquilarSocioProductos(1, [1, 2, 3]);
        // No debe lanzar excepción (está capturada), sin alquileres
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba devolución de producto a cliente no encontrado
     */
    public function testDevolverSocioProductoClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->devolverSocioProducto(999, 1);
        // No debe lanzar excepción (está capturada)
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba devolución de producto no encontrado
     */
    public function testDevolverSocioProductoProductoNoEncontrado()
    {
        $this->videoclub->incluirSocio('Juan', 3);
        $this->videoclub->devolverSocioProducto(1, 999);
        // No debe lanzar excepción (está capturada)
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba devolución múltiple con cliente no encontrado
     */
    public function testDevolverSocioProductosClienteNoEncontrado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        
        // Debe lanzar ClienteNoExisteException cuando el cliente no existe
        $this->expectException(ClienteNoExisteException::class);
        $this->videoclub->devolverSocioProductos(999, [1, 2]);
    }

    /**
     * @test
     * Prueba devolución múltiple con producto no alquilado
     */
    public function testDevolverSocioProductosSoporteNoAlquilado()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        $this->videoclub->incluirSocio('Juan', 3);
        
        // Intentar devolver sin haber alquilado
        $this->videoclub->devolverSocioProductos(1, [1, 2]);
        // No debe lanzar excepción (está capturada)
        $this->assertEquals(0, $this->videoclub->getSocios()[0]->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba listar productos vacío
     */
    public function testListarProductosVacio()
    {
        $this->videoclub->listarProductos();
        // No debe lanzar excepción
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba listar productos con contenido
     */
    public function testListarProductosConContenido()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirDvd('Inception', 4.5, ['ES', 'EN'], '16:9', 148);
        $this->videoclub->listarProductos();
        // No debe lanzar excepción
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba listar socios vacío
     */
    public function testListarSociosVacio()
    {
        $this->videoclub->listarSocios();
        // No debe lanzar excepción
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba listar socios con contenido
     */
    public function testListarSociosConContenido()
    {
        $this->videoclub->incluirSocio('Juan', 2);
        $this->videoclub->incluirSocio('Maria', 3);
        $this->videoclub->listarSocios();
        // No debe lanzar excepción
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba que se lanza ClienteNoExisteException al alquilar múltiples productos con cliente inexistente
     */
    public function testAlquilarMultiplesConClienteNoExisteException()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        
        // Debe lanzar ClienteNoExisteException cuando el cliente no existe
        $this->expectException(ClienteNoExisteException::class);
        $this->expectExceptionMessage('Cliente con número 999 no existe');
        
        $this->videoclub->alquilarSocioProductos(999, [1, 2]);
    }

    /**
     * @test
     * Prueba que se lanza ClienteNoExisteException al devolver múltiples productos con cliente inexistente
     */
    public function testDevolverMultiplesConClienteNoExisteException()
    {
        $this->videoclub->incluirCintaVideo('Avatar', 3.5, 120);
        $this->videoclub->incluirCintaVideo('Titanic', 2.5, 194);
        
        // Debe lanzar ClienteNoExisteException cuando el cliente no existe
        $this->expectException(ClienteNoExisteException::class);
        $this->expectExceptionMessage('Cliente con número 999 no existe');
        
        $this->videoclub->devolverSocioProductos(999, [1, 2]);
    }
}
