<?php
include "Soporte.php";

$Soporte1 = new Soporte("Tenet", 22, 3);
echo "<strong>" . $Soporte1->getTitulo() . "</strong><br>";
echo "<br>Precio: " . $Soporte1->getPrecio() . " euros<br>";
echo "<br>Precio IVA incluido: " . $Soporte1->getPrecioConIVA() . " euros";
$Soporte1->muestraResumen();
?>
