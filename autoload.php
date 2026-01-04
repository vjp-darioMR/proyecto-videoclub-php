<?php
//AutoLoad para cargar las clases automaticamente
spl_autoload_register( function( $nombreClase ) {
    // Manejo especial para Monolog
    if (strpos($nombreClase, 'Monolog\\') === 0) {
        // Monolog está en vendor/monolog/Monolog/
        $path = "vendor/monolog/".str_replace("\\", "/", $nombreClase).'.php';
        if (file_exists($path)) {
            include_once $path;
            return;
        }
    }
    
    //La ruta esta dentro de app, pero también dentro del namespace Dwes\ProyectoVideoclub
    //Por tanto se debe crear app/ y el namespace como carpetas
    include_once "app/".str_replace("\\", "/", $nombreClase).'.php';
} );
?>