<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\CintaVideo;

class SoporteTest extends TestCase
{
    private $soporte;

    protected function setUp(): void
    {
        // Usamos CintaVideo como implementación concreta de Soporte (que es abstracta)
        $this->soporte = new CintaVideo("Test Soporte", 99, 2.99, 90);
    }

    public function testConstructInitializesProperties()
    {
        $this->assertEquals("Test Soporte", $this->soporte->getTitulo());
        $this->assertEquals(99, $this->soporte->getNumero());
        $this->assertEquals(2.99, $this->soporte->getPrecio());
    }

    public function testGetTitulo()
    {
        $this->assertEquals("Test Soporte", $this->soporte->getTitulo());
    }

    public function testGetNumero()
    {
        $this->assertEquals(99, $this->soporte->getNumero());
    }

    public function testGetPrecio()
    {
        $this->assertEquals(2.99, $this->soporte->getPrecio());
    }

    public function testGetPrecioConIVACalculation()
    {
        $precioSinIVA = 100;
        $soporte = new CintaVideo("Test", 1, $precioSinIVA, 60);
        $precioConIVA = $soporte->getPrecioConIVA();
        
        // IVA es 21%
        $this->assertEquals($precioSinIVA * 1.21, $precioConIVA);
    }

    public function testAlquiladoDefaultIsFalse()
    {
        $this->assertFalse($this->soporte->alquilado);
    }

    public function testAlquiladoCanBeSet()
    {
        $this->soporte->alquilado = true;
        $this->assertTrue($this->soporte->alquilado);
        
        $this->soporte->alquilado = false;
        $this->assertFalse($this->soporte->alquilado);
    }

    public function testMuestraResumenReturnsString()
    {
        $resultado = $this->soporte->muestraResumen();
        $this->assertIsString($resultado);
    }

    public function testMuestraResumenContainsBasicInfo()
    {
        $resultado = $this->soporte->muestraResumen();
        $this->assertStringContainsString("Test Soporte", $resultado);
        $this->assertStringContainsString("Precio", $resultado);
        $this->assertStringContainsString("€", $resultado);
    }

    public function testMuestraResumenContainsNumber()
    {
        $resultado = $this->soporte->muestraResumen();
        $this->assertStringContainsString("99", $resultado);
    }

    public function testMuestraResumenIsValidHTML()
    {
        $resultado = $this->soporte->muestraResumen();
        $this->assertStringContainsString("<div", $resultado);
        $this->assertStringContainsString("</div>", $resultado);
        $this->assertStringContainsString("card", $resultado);
    }

    public function testPrecioConIVAIsGreaterThanPrecio()
    {
        $this->assertGreaterThan($this->soporte->getPrecio(), $this->soporte->getPrecioConIVA());
    }

    public function testDifferentPrices()
    {
        $soporte1 = new CintaVideo("Película 1", 1, 5.00, 90);
        $soporte2 = new CintaVideo("Película 2", 2, 10.00, 120);
        
        $this->assertEquals(5.00, $soporte1->getPrecio());
        $this->assertEquals(10.00, $soporte2->getPrecio());
        $this->assertNotEquals($soporte1->getPrecio(), $soporte2->getPrecio());
    }

    public function testPrecioConIVAFormatted()
    {
        $precioSinIVA = 50;
        $soporte = new CintaVideo("Test", 1, $precioSinIVA, 60);
        $precioConIVA = $soporte->getPrecioConIVA();
        
        // Debe ser exactamente 50 * 1.21 = 60.5
        $this->assertEquals(60.5, $precioConIVA);
    }

    public function testPrecioConIVAForZero()
    {
        $soporte = new CintaVideo("Free", 1, 0.00, 60);
        $this->assertEquals(0.00, $soporte->getPrecioConIVA());
    }

    public function testMuestraResumenContainsPrecioConIVA()
    {
        $soporte = new CintaVideo("Test", 1, 10.00, 60);
        $resultado = $soporte->muestraResumen();
        
        // Debe contener tanto el precio sin IVA como con IVA
        $this->assertStringContainsString("10", $resultado);
        $this->assertStringContainsString("12.1", $resultado);
    }

    public function testMuestraResumenEchos()
    {
        // Capturar salida de echo
        ob_start();
        $this->soporte->muestraResumen();
        $output = ob_get_clean();
        
        // Debe haber salida
        $this->assertNotEmpty($output);
        $this->assertStringContainsString("badge", $output);
    }
}
