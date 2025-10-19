<?php
//AutoLoad para cargar las clases automaticamente
spl_autoload_register( function( $nombreClase ) {
    //La ruta esta dentro de app, pero también dentro del namespace Dwes\ProyectoVideoclub
    //Por tanto se debe crear app/ y el namespace como carpetas
    include_once "app/".str_replace("\\", "/", $nombreClase).'.php';
} );
?>