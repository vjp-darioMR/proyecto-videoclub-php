<?php
namespace Monolog;

class Logger
{
    const INFO = 'info';
    const DEBUG = 'debug';
    const ERROR = 'error';
    const WARNING = 'warning';

    private $name;
    private $path;

    public function __construct($name = 'app', $path = null)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function info($message, array $context = [])
    {
        $this->log(self::INFO, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->log(self::DEBUG, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log(self::ERROR, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log(self::WARNING, $message, $context);
    }

    public function log($level, $message, array $context = [])
    {
        $time = date('Y-m-d H:i:s');
        $ctx = !empty($context) ? ' ' . json_encode($context) : '';
        $line = "[{$time}] {$this->name}.{$level}: {$message}{$ctx}\n";

        if ($this->path) {
            $dir = dirname($this->path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            @file_put_contents($this->path, $line, FILE_APPEND | LOCK_EX);
        } else {
            error_log($line);
        }
    }
}
