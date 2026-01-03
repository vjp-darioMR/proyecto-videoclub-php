<?php

namespace Dwes\ProyectoVideoclub\Util;

use Monolog\Logger;

class LogFactory
{
    /**
     * Crear un logger para el proyecto que escriba en logs/videoclub.log
     * @param string $channel
     * @return Logger
     */
    public static function createLogger(string $channel = 'VideoclubLogger'): Logger
    {
        $projectRoot = dirname(__DIR__, 3);
        $logDir = $projectRoot . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logPath = $logDir . DIRECTORY_SEPARATOR . 'videoclub.log';

        return new Logger($channel, $logPath);
    }
}
