<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\CintaVideo;

class CintaVideoTest extends TestCase
{
    private $cintaVideo;

    protected function setUp(): void
    {
        $this->cintaVideo = new CintaVideo("Avatar", 1, 3.50, 120);
    }

    public function testConstruct()
    {
        $this->assertEquals("Avatar", $this->cintaVideo->getTitulo());
        $this->assertEquals(1, $this->cintaVideo->getNumero());
        $this->assertEquals(3.50, $this->cintaVideo->getPrecio());
    }

    public function testGetTitulo()
    {
        $this->assertEquals("Avatar", $this->cintaVideo->getTitulo());
    }

    public function testGetNumero()
    {
        $this->assertEquals(1, $this->cintaVideo->getNumero());
    }

    public function testGetPrecio()
    {
        $this->assertEquals(3.50, $this->cintaVideo->getPrecio());
    }

    public function testGetPrecioConIVA()
    {
        $precioEsperado = 3.50 * 1.21;
        $this->assertEquals($precioEsperado, $this->cintaVideo->getPrecioConIVA());
    }

    public function testAlquiladoDefault()
    {
        $this->assertFalse($this->cintaVideo->alquilado);
    }

    public function testSetAlquilado()
    {
        $this->cintaVideo->alquilado = true;
        $this->assertTrue($this->cintaVideo->alquilado);
    }

    public function testMuestraResumenReturnsString()
    {
        $resultado = $this->cintaVideo->muestraResumen();
        $this->assertIsString($resultado);
    }

    public function testMuestraResumenContainsTitle()
    {
        $resultado = $this->cintaVideo->muestraResumen();
        $this->assertStringContainsString("Avatar", $resultado);
    }

    public function testMuestraResumenContainsDuration()
    {
        $resultado = $this->cintaVideo->muestraResumen();
        $this->assertStringContainsString("120", $resultado);
        $this->assertStringContainsString("minutos", $resultado);
    }

    public function testMuestraResumenContainsPrecio()
    {
        $resultado = $this->cintaVideo->muestraResumen();
        $this->assertStringContainsString("3.5", $resultado);
    }

    public function testMuestraResumenIsHTML()
    {
        $resultado = $this->cintaVideo->muestraResumen();
        $this->assertStringContainsString("<div", $resultado);
        $this->assertStringContainsString("card", $resultado);
    }
}
