<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\Bluray;

class BlurayTest extends TestCase
{
    private $bluray;

    protected function setUp(): void
    {
        $this->bluray = new Bluray("Avatar: The Way of Water", 1, 5.50, 192, true);
    }

    /**
     * @test
     * Prueba construcción básica del Bluray
     */
    public function testConstruct()
    {
        $this->assertEquals("Avatar: The Way of Water", $this->bluray->getTitulo());
        $this->assertEquals(1, $this->bluray->getNumero());
        $this->assertEquals(5.50, $this->bluray->getPrecio());
    }

    /**
     * @test
     * Prueba obtener título
     */
    public function testGetTitulo()
    {
        $this->assertEquals("Avatar: The Way of Water", $this->bluray->getTitulo());
    }

    /**
     * @test
     * Prueba obtener número
     */
    public function testGetNumero()
    {
        $this->assertEquals(1, $this->bluray->getNumero());
    }

    /**
     * @test
     * Prueba obtener precio
     */
    public function testGetPrecio()
    {
        $this->assertEquals(5.50, $this->bluray->getPrecio());
    }

    /**
     * @test
     * Prueba precio con IVA
     */
    public function testGetPrecioConIVA()
    {
        $precioEsperado = 5.50 * 1.21;
        $this->assertEquals($precioEsperado, $this->bluray->getPrecioConIVA());
    }

    /**
     * @test
     * Prueba alquilado por defecto es false
     */
    public function testAlquiladoDefault()
    {
        $this->assertFalse($this->bluray->alquilado);
    }

    /**
     * @test
     * Prueba establecer alquilado
     */
    public function testSetAlquilado()
    {
        $this->bluray->alquilado = true;
        $this->assertTrue($this->bluray->alquilado);
    }

    /**
     * @test
     * Prueba obtener duración
     */
    public function testGetDuracion()
    {
        $this->assertEquals(192, $this->bluray->getDuracion());
    }

    /**
     * @test
     * Prueba obtener is4k
     */
    public function testGetIs4k()
    {
        $this->assertTrue($this->bluray->getIs4k());
    }

    /**
     * @test
     * Prueba constructor con is4k false
     */
    public function testConstructorConIs4kFalse()
    {
        $bluray = new Bluray("Interstellar", 10, 4.50, 169, false);
        $this->assertFalse($bluray->getIs4k());
        $this->assertEquals(169, $bluray->getDuracion());
    }

    /**
     * @test
     * Prueba muestraResumen retorna string
     */
    public function testMuestraResumenReturnsString()
    {
        $resultado = $this->bluray->muestraResumen();
        $this->assertIsString($resultado);
    }

    /**
     * @test
     * Prueba muestraResumen contiene título
     */
    public function testMuestraResumenContainsTitle()
    {
        $resultado = $this->bluray->muestraResumen();
        $this->assertStringContainsString("Avatar: The Way of Water", $resultado);
    }

    /**
     * @test
     * Prueba muestraResumen contiene duración
     */
    public function testMuestraResumenContainsDuration()
    {
        $resultado = $this->bluray->muestraResumen();
        $this->assertStringContainsString("192", $resultado);
        $this->assertStringContainsString("Duración", $resultado);
    }

    /**
     * @test
     * Prueba muestraResumen contiene 4K
     */
    public function testMuestraResumenContains4K()
    {
        $resultado = $this->bluray->muestraResumen();
        $this->assertStringContainsString("4K", $resultado);
    }

    /**
     * @test
     * Prueba muestraResumen no contiene 4K si no es 4K
     */
    public function testMuestraResumenNoContains4KWhenNotIs4k()
    {
        $bluray = new Bluray("Standard Bluray", 11, 3.50, 120, false);
        $resultado = $bluray->muestraResumen();
        $this->assertStringNotContainsString("4K", $resultado);
        $this->assertStringContainsString("Standard", $resultado);
    }

    /**
     * @test
     * Prueba muestraResumen es HTML válido
     */
    public function testMuestraResumenIsValidHTML()
    {
        $resultado = $this->bluray->muestraResumen();
        $this->assertStringContainsString("<div", $resultado);
        $this->assertStringContainsString("card", $resultado);
        $this->assertStringContainsString("border-info", $resultado);
    }
}

?>
