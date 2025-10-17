<?php
include "Soporte.php";
include "CintaVideo.php";
include "Dvd.php";

$Soporte1 = new Soporte("Tenet", 22, 3);
echo "<h3>" . $Soporte1->getTitulo() . "</h3>"; 
$Soporte1->muestraResumen();


$SmiCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 127);
echo "<h3>" . $SmiCinta->getTitulo() . "</h3>"; 
$SmiCinta->muestraResumen();


$SmiDvd = new Dvd("Origen", 24, 15, "Español, Ingles, Frances", "16:9");
echo "<h3>" . $SmiDvd->getTitulo() . "</h3>";
$SmiDvd->muestraResumen();
?>