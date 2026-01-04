<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\Dvd;

class DvdTest extends TestCase
{
    private $dvd;

    protected function setUp(): void
    {
        $this->dvd = new Dvd("Inception", 2, 4.50, "ES,EN", "16:9");
    }

    public function testConstruct()
    {
        $this->assertEquals("Inception", $this->dvd->getTitulo());
        $this->assertEquals(2, $this->dvd->getNumero());
        $this->assertEquals(4.50, $this->dvd->getPrecio());
    }

    public function testGetTitulo()
    {
        $this->assertEquals("Inception", $this->dvd->getTitulo());
    }

    public function testGetNumero()
    {
        $this->assertEquals(2, $this->dvd->getNumero());
    }

    public function testGetPrecio()
    {
        $this->assertEquals(4.50, $this->dvd->getPrecio());
    }

    public function testGetPrecioConIVA()
    {
        $precioEsperado = 4.50 * 1.21;
        $this->assertEquals($precioEsperado, $this->dvd->getPrecioConIVA());
    }

    public function testAlquiladoDefault()
    {
        $this->assertFalse($this->dvd->alquilado);
    }

    public function testSetAlquilado()
    {
        $this->dvd->alquilado = true;
        $this->assertTrue($this->dvd->alquilado);
    }

    public function testMuestraResumenReturnsString()
    {
        $resultado = $this->dvd->muestraResumen();
        $this->assertIsString($resultado);
    }

    public function testMuestraResumenContainsTitle()
    {
        $resultado = $this->dvd->muestraResumen();
        $this->assertStringContainsString("Inception", $resultado);
    }

    public function testMuestraResumenContainsLanguages()
    {
        $resultado = $this->dvd->muestraResumen();
        $this->assertStringContainsString("ES,EN", $resultado);
        $this->assertStringContainsString("Idiomas", $resultado);
    }

    public function testMuestraResumenContainsFormat()
    {
        $resultado = $this->dvd->muestraResumen();
        $this->assertStringContainsString("16:9", $resultado);
        $this->assertStringContainsString("Formato", $resultado);
    }

    public function testMuestraResumenIsHTML()
    {
        $resultado = $this->dvd->muestraResumen();
        $this->assertStringContainsString("<div", $resultado);
        $this->assertStringContainsString("card", $resultado);
        $this->assertStringContainsString("border-warning", $resultado);
    }
}
