<?php

namespace Dwes\ProyectoVideoclub\Util;

interface LogInterface
{
    public function info(string $message, array $context = []);
    public function debug(string $message, array $context = []);
    public function warning(string $message, array $context = []);
    public function error(string $message, array $context = []);
    public function log(string $level, string $message, array $context = []);
}
