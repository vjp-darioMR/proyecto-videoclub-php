<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\Cliente;
use Dwes\ProyectoVideoclub\CintaVideo;
use Dwes\ProyectoVideoclub\Dvd;
use Dwes\ProyectoVideoclub\Juego;
use Dwes\ProyectoVideoclub\Exception\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Exception\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Exception\SoporteNoEncontradoException;

class ClienteTest extends TestCase
{
    private $cliente;
    private $cintas;
    private $dvds;
    private $juegos;

    protected function setUp(): void
    {
        // Cliente base para pruebas
        $this->cliente = new Cliente('Test Cliente', 1, 2, 'testuser', 'pass123');

        // Soportes de prueba
        $this->cintas = [
            new CintaVideo('Avatar', 1, 3.5, 120),
            new CintaVideo('Titanic', 2, 2.5, 194),
            new CintaVideo('Jaws', 3, 2.0, 124),
        ];

        $this->dvds = [
            new Dvd('Inception', 4, 4.5, ['ES', 'EN'], '16:9'),
            new Dvd('Interstellar', 5, 5.0, ['ES', 'EN'], '21:9'),
            new Dvd('The Matrix', 6, 3.0, ['ES', 'EN', 'FR'], '16:9'),
        ];

        $this->juegos = [
            new Juego('Elden Ring', 7, 59.99, 'PS5', 1, 4),
            new Juego('God of War', 8, 49.99, 'PS5', 1, 1),
            new Juego('Zelda', 9, 59.99, 'Switch', 1, 1),
        ];
    }

    /**
     * @test
     * Comprueba los datos básicos del cliente
     */
    public function testCreacionClienteConDatos()
    {
        $this->assertEquals(1, $this->cliente->getNumero());
        $this->assertEquals('Test Cliente', $this->cliente->getNombre());
        $this->assertEquals('testuser', $this->cliente->getUsername());
        $this->assertEquals('pass123', $this->cliente->getPassword());
        $this->assertEquals(2, $this->cliente->getMaxAlquilerConcurrente());
        $this->assertEquals(0, $this->cliente->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba alquiler exitoso de un soporte
     */
    public function testAlquilerExitoso()
    {
        $this->cliente->alquilar($this->cintas[0]);
        $this->assertEquals(1, $this->cliente->getNumSoportesAlquilados());
        $this->assertTrue($this->cliente->tieneAlquilado($this->cintas[0]));
    }

    /**
     * @test
     * Prueba que al alquilar un soporte ya alquilado lanza excepción
     */
    public function testAlquilerSoporteYaAlquiladoLanzaExcepcion()
    {
        $this->cliente->alquilar($this->cintas[0]);
        
        $this->expectException(SoporteYaAlquiladoException::class);
        $this->expectExceptionMessage("El soporte ya está alquilado por este cliente.");
        
        $this->cliente->alquilar($this->cintas[0]);
    }

    /**
     * @test
     * Prueba que al superar el cupo lanza excepción
     */
    public function testAlquilerSuperandoCupoLanzaExcepcion()
    {
        $this->cliente->alquilar($this->cintas[0]);
        $this->cliente->alquilar($this->dvds[0]);
        
        // Ahora tiene 2 alquileres con cupo de 2, intenta alquilar otro
        $this->expectException(CupoSuperadoException::class);
        $this->expectExceptionMessage("No se puede alquilar: se ha superado el maximo de 2 alquileres.");
        
        $this->cliente->alquilar($this->juegos[0]);
    }

    /**
     * @test
     * Prueba devolución exitosa de un soporte
     */
    public function testDevolucionExitosa()
    {
        $this->cliente->alquilar($this->cintas[0]);
        $this->assertEquals(1, $this->cliente->getNumSoportesAlquilados());
        
        $this->cliente->devolver($this->cintas[0]->getNumero());
        $this->assertEquals(0, $this->cliente->getNumSoportesAlquilados());
        $this->assertFalse($this->cliente->tieneAlquilado($this->cintas[0]));
    }

    /**
     * @test
     * Prueba que devolver un soporte no alquilado lanza excepción
     */
    public function testDevolverSoporteNoAlquiladoLanzaExcepcion()
    {
        $this->expectException(SoporteNoEncontradoException::class);
        $this->expectExceptionMessage("No se puede devolver: el soporte con número 1 no está alquilado.");
        
        $this->cliente->devolver(1);
    }

    /**
     * @test
     * Prueba que la devolución con ID incorrecto no se realiza
     */
    public function testDevolverSoporteConIdIncorrectoNoSeRealiza()
    {
        $this->cliente->alquilar($this->cintas[0]); // Alquila soporte número 1
        
        // Intenta devolver soporte con número 999
        $this->expectException(SoporteNoEncontradoException::class);
        $this->cliente->devolver(999);
        
        // Verifica que sigue alquilado
        $this->assertEquals(1, $this->cliente->getNumSoportesAlquilados());
    }

    /**
     * Proveedor de datos para probar diferentes cupos
     */
    public static function proveedorCupos(): array
    {
        return [
            'cupo 1' => [1, 1],
            'cupo 2' => [2, 2],
            'cupo 3' => [3, 3],
            'cupo 5' => [5, 5],
            'cupo 10' => [10, 10],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorCupos
     * Prueba alquileres con diferentes cupos
     */
    public function testAlquileresConDiferentesCupos($cupoAsignado, $soportesAAlquilar)
    {
        $cliente = new Cliente('Cliente Cupo Test', 99, $cupoAsignado);
        
        // Crea y alquila soportes según el cupo
        $soportesAux = [];
        for ($i = 0; $i < $soportesAAlquilar; $i++) {
            $soporte = new CintaVideo("Película $i", 100 + $i, 2.5, 120);
            $soportesAux[] = $soporte;
            $cliente->alquilar($soporte);
        }
        
        $this->assertEquals($soportesAAlquilar, $cliente->getNumSoportesAlquilados());
        $this->assertEquals($cupoAsignado, $cliente->getMaxAlquilerConcurrente());
    }

    /**
     * Proveedor de datos para probar exceso de cupo
     */
    public static function proveedorExcesoCupo(): array
    {
        return [
            'exceso cupo 1' => [1, 2],
            'exceso cupo 2' => [2, 3],
            'exceso cupo 3' => [3, 4],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorExcesoCupo
     * Prueba que superar el cupo lanza excepción con diferentes valores
     */
    public function testExcesoCupoConDiferentesValores($cupo, $intentoAlquilar)
    {
        $cliente = new Cliente('Cliente Exceso', 98, $cupo);
        
        // Alquila hasta el cupo
        for ($i = 0; $i < $cupo; $i++) {
            $soporte = new CintaVideo("Película $i", 200 + $i, 2.5, 120);
            $cliente->alquilar($soporte);
        }
        
        $this->assertEquals($cupo, $cliente->getNumSoportesAlquilados());
        
        // Intenta alquilar uno más
        $this->expectException(CupoSuperadoException::class);
        
        for ($i = $cupo; $i < $intentoAlquilar; $i++) {
            $soporte = new CintaVideo("Película Extra $i", 300 + $i, 2.5, 120);
            $cliente->alquilar($soporte);
        }
    }

    /**
     * Proveedor de datos para probar diferentes tipos de soportes
     */
    public static function proveedorTiposSoportes(): array
    {
        return [
            'cinta video' => [new CintaVideo('Avatar', 50, 3.5, 120), 50],
            'dvd' => [new Dvd('Inception', 51, 4.5, ['ES', 'EN'], '16:9'), 51],
            'juego' => [new Juego('Elden Ring', 52, 59.99, 'PS5', 1, 4), 52],
        ];
    }

    /**
     * @test
     * @dataProvider proveedorTiposSoportes
     * Prueba alquiler de diferentes tipos de soportes
     */
    public function testAlquilerDiferentesTiposSoportes($soporte, $numeroEsperado)
    {
        $cliente = new Cliente('Cliente Tipos', 97, 3);
        $cliente->alquilar($soporte);
        
        $this->assertEquals(1, $cliente->getNumSoportesAlquilados());
        $this->assertTrue($cliente->tieneAlquilado($soporte));
        $this->assertEquals($numeroEsperado, $soporte->getNumero());
    }

    /**
     * @test
     * Prueba múltiples alquileres y devoluciones secuenciales
     */
    public function testMultiplesAlquileresYDevoluciones()
    {
        $cliente = new Cliente('Cliente Multiples', 96, 3);
        
        // Alquila 3 soportes diferentes
        $cliente->alquilar($this->cintas[0]);
        $cliente->alquilar($this->dvds[0]);
        $cliente->alquilar($this->juegos[0]);
        
        $this->assertEquals(3, $cliente->getNumSoportesAlquilados());
        
        // Devuelve el primero
        $cliente->devolver($this->cintas[0]->getNumero());
        $this->assertEquals(2, $cliente->getNumSoportesAlquilados());
        $this->assertFalse($cliente->tieneAlquilado($this->cintas[0]));
        
        // Devuelve el segundo
        $cliente->devolver($this->dvds[0]->getNumero());
        $this->assertEquals(1, $cliente->getNumSoportesAlquilados());
        $this->assertFalse($cliente->tieneAlquilado($this->dvds[0]));
        
        // Ahora puede alquilar dos más
        $cliente->alquilar($this->cintas[1]);
        $cliente->alquilar($this->dvds[1]);
        
        $this->assertEquals(3, $cliente->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba que getAlquileres devuelve array con soportes alquilados
     */
    public function testGetAlquileresDevuelveArraySoportes()
    {
        $cliente = new Cliente('Cliente Array', 95, 3);
        
        $this->assertEmpty($cliente->getAlquileres());
        
        $cliente->alquilar($this->cintas[0]);
        $alquileres = $cliente->getAlquileres();
        
        $this->assertCount(1, $alquileres);
        $this->assertEquals($this->cintas[0], $alquileres[0]);
    }

    /**
     * @test
     * Prueba los setters de Cliente
     */
    public function testSetters()
    {
        $cliente = new Cliente('Original', 94, 2);
        
        $cliente->setNombre('Modificado');
        $this->assertEquals('Modificado', $cliente->getNombre());
        
        $cliente->setUsername('newuser');
        $this->assertEquals('newuser', $cliente->getUsername());
        
        $cliente->setPassword('newpass');
        $this->assertEquals('newpass', $cliente->getPassword());
        
        $cliente->setMaxAlquilerConcurrente(5);
        $this->assertEquals(5, $cliente->getMaxAlquilerConcurrente());
    }

    /**
     * @test
     * Prueba que alquilar devuelve el objeto cliente (fluent interface)
     */
    public function testAlquilarDevuelveCliente()
    {
        $resultado = $this->cliente->alquilar($this->cintas[0]);
        $this->assertInstanceOf(Cliente::class, $resultado);
        $this->assertEquals($this->cliente, $resultado);
    }

    /**
     * @test
     * Prueba que devolver devuelve el objeto cliente (fluent interface)
     */
    public function testDevolverDevuelveCliente()
    {
        $this->cliente->alquilar($this->cintas[0]);
        $resultado = $this->cliente->devolver($this->cintas[0]->getNumero());
        $this->assertInstanceOf(Cliente::class, $resultado);
        $this->assertEquals($this->cliente, $resultado);
    }

    /**
     * @test
     * Prueba que no puede haber dos clientes con el mismo número alquilando el mismo soporte
     */
    public function testDosSoportesNoSeMezclanlientes()
    {
        $cliente1 = new Cliente('Cliente 1', 1, 2);
        $cliente2 = new Cliente('Cliente 2', 2, 2);
        
        $soporte = new CintaVideo('Avatar', 1, 3.5, 120);
        
        $cliente1->alquilar($soporte);
        $this->assertTrue($cliente1->tieneAlquilado($soporte));
        $this->assertFalse($cliente2->tieneAlquilado($soporte));
    }

    /**
     * @test
     * Prueba listar alquileres del cliente
     */
    public function testListarAlquileresVacio()
    {
        $cliente = new Cliente('Test', 1, 5);
        $cliente->listarAlquileres();
        // No debe lanzar excepción
        $this->assertTrue(true);
    }

    /**
     * @test
     * Prueba listar alquileres con contenido
     */
    public function testListarAlquileresConContenido()
    {
        $cliente = new Cliente('Test', 1, 5);
        $cliente->alquilar($this->cintas[0]);
        $cliente->alquilar($this->dvds[0]);
        $cliente->listarAlquileres();
        // No debe lanzar excepción
        $this->assertEquals(2, $cliente->getNumSoportesAlquilados());
    }

    /**
     * @test
     * Prueba métodos getter de Cliente
     */
    public function testClienteGetters()
    {
        $cliente = new Cliente('Juan Pérez', 5, 3, 'juan', 'pass123');
        
        // Verificar todos los getters funcionan correctamente
        $this->assertEquals('Juan Pérez', $cliente->getNombre());
        $this->assertEquals(5, $cliente->getNumero());
        $this->assertEquals(3, $cliente->getMaxAlquilerConcurrente());
        $this->assertEquals('juan', $cliente->getUsername());
        $this->assertEquals('pass123', $cliente->getPassword());
    }

    /**
     * @test
     * Prueba obtener alquileres como array
     */
    public function testGetAlquileres()
    {
        $cliente = new Cliente('Test', 1, 5);
        $alquileres = $cliente->getAlquileres();
        
        $this->assertIsArray($alquileres);
        $this->assertCount(0, $alquileres);
        
        // Alquilar algunos soportes
        $cliente->alquilar($this->cintas[0]);
        $cliente->alquilar($this->dvds[0]);
        
        $alquileres = $cliente->getAlquileres();
        $this->assertCount(2, $alquileres);
    }

    /**
     * @test
     * Prueba obtener soporte específico de alquileres
     */
    public function testObtenerSoporteAlquilado()
    {
        $cliente = new Cliente('Test', 1, 5);
        $cliente->alquilar($this->cintas[0]);
        $cliente->alquilar($this->dvds[0]);
        
        $alquileres = $cliente->getAlquileres();
        $this->assertEquals('Avatar', $alquileres[0]->getTitulo());
        $this->assertEquals('Inception', $alquileres[1]->getTitulo());
    }

    /**
     * @test
     * Prueba obtener getter de username
     */
    public function testObtenerUsername()
    {
        $cliente = new Cliente('Test', 1, 3, 'miusuario', 'mipass');
        $this->assertEquals('miusuario', $cliente->getUsername());
    }

    /**
     * @test
     * Prueba obtener getter de password
     */
    public function testObtenerPassword()
    {
        $cliente = new Cliente('Test', 1, 3, 'miusuario', 'mipass');
        $this->assertEquals('mipass', $cliente->getPassword());
    }

    /**
     * @test
     * Prueba cliente sin username/password
     */
    public function testClienteSinCredenciales()
    {
        $cliente = new Cliente('Test', 1, 3);
        $this->assertEquals('', $cliente->getUsername());
        $this->assertEquals('', $cliente->getPassword());
    }
}
