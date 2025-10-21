<?php
//Cambiamos los require_once por use para cargar las clases
use Dwes\ProyectoVideoclub\Soporte;
use Dwes\ProyectoVideoclub\CintaVideo;
use Dwes\ProyectoVideoclub\Dvd;
use Dwes\ProyectoVideoclub\Juego;

$Soporte1 = new Soporte("Tenet", 22, 3);
echo "<h3>" . $Soporte1->getTitulo() . "</h3>"; 
$Soporte1->muestraResumen();


$miCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 107);
echo "<h3>" . $miCinta->getTitulo() . "</h3>"; 
$miCinta->muestraResumen();


$miDvd = new Dvd("Origen", 24, 15, "Español, Ingles, Frances", "16:9");
echo "<h3>" . $miDvd->getTitulo() . "</h3>";
$miDvd->muestraResumen();

$mijuego1 = new Juego("The Last of Us", 26, 49.99, "PS4", 1, 1);
echo "<h3>" . $mijuego1->getTitulo() . "</h3>";
$mijuego1->muestraResumen();

?>