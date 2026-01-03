<?php
namespace Monolog;

// Minimal Logger stub implementing a tiny subset of Monolog's API.
class Logger
{
    const INFO = 'info';
    const DEBUG = 'debug';
    const ERROR = 'error';

    private $name;

    public function __construct($name = 'app')
    {
        $this->name = $name;
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

    public function log($level, $message, array $context = [])
    {
        $time = date('Y-m-d H:i:s');
        $ctx = !empty($context) ? json_encode($context) : '';
        error_log("[{$time}] {$this->name}.{$level}: {$message} {$ctx}");
    }
}
