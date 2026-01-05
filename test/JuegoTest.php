<?php

namespace Dwes\ProyectoVideoclub\Tests;

use PHPUnit\Framework\TestCase;
use Dwes\ProyectoVideoclub\Juego;

class JuegoTest extends TestCase
{
    private $juego;

    protected function setUp(): void
    {
        $this->juego = new Juego("Elden Ring", 3, 59.99, "PS5", 1, 4);
    }

    public function testConstruct()
    {
        $this->assertEquals("Elden Ring", $this->juego->getTitulo());
        $this->assertEquals(3, $this->juego->getNumero());
        $this->assertEquals(59.99, $this->juego->getPrecio());
    }

    public function testGetTitulo()
    {
        $this->assertEquals("Elden Ring", $this->juego->getTitulo());
    }

    public function testGetNumero()
    {
        $this->assertEquals(3, $this->juego->getNumero());
    }

    public function testGetPrecio()
    {
        $this->assertEquals(59.99, $this->juego->getPrecio());
    }

    public function testGetPrecioConIVA()
    {
        $precioEsperado = 59.99 * 1.21;
        $this->assertEquals($precioEsperado, $this->juego->getPrecioConIVA());
    }

    public function testAlquiladoDefault()
    {
        $this->assertFalse($this->juego->alquilado);
    }

    public function testSetAlquilado()
    {
        $this->juego->alquilado = true;
        $this->assertTrue($this->juego->alquilado);
    }

    public function testMuestraResumenReturnsString()
    {
        $resultado = $this->juego->muestraResumen();
        $this->assertIsString($resultado);
    }

    public function testMuestraResumenContainsTitle()
    {
        $resultado = $this->juego->muestraResumen();
        $this->assertStringContainsString("Elden Ring", $resultado);
    }

    public function testMuestraResumenContainsConsole()
    {
        $resultado = $this->juego->muestraResumen();
        $this->assertStringContainsString("PS5", $resultado);
        $this->assertStringContainsString("Consola", $resultado);
    }

    public function testMuestraResumenContainsPlayers()
    {
        $resultado = $this->juego->muestraResumen();
        $this->assertStringContainsString("1", $resultado);
        $this->assertStringContainsString("4", $resultado);
        $this->assertStringContainsString("jugadores", $resultado);
    }

    public function testMuestraResumenIsHTML()
    {
        $resultado = $this->juego->muestraResumen();
        $this->assertStringContainsString("<div", $resultado);
        $this->assertStringContainsString("card", $resultado);
        $this->assertStringContainsString("border-info", $resultado);
    }
}
