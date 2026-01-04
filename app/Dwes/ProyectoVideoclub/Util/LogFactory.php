<?php

namespace Dwes\ProyectoVideoclub\Util;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogFactory
{
    /**
     * Crear un logger para el proyecto que escriba en logs/videoclub.log
     * mostrando todos los mensajes desde debug
     * @param string $channel
     * @return \Psr\Log\LoggerInterface
     */
    public static function createLogger(string $channel = 'VideoclubLogger'): \Psr\Log\LoggerInterface
    {
        $projectRoot = dirname(__DIR__, 4);
        $logDir = $projectRoot . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logPath = $logDir . DIRECTORY_SEPARATOR . 'videoclub.log';

        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
        return $logger;
    }
}
