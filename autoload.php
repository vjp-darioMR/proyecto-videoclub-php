<?php
spl_autoload_register( function( $nombreClase ) {
    include_once "app/".str_replace("\\", "/", $nombreClase).'.php';
} );
?>