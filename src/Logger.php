<?php

declare(strict_types=1);

namespace Exewen\Logger;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Logger\Contract\LoggerInterface;

class Logger extends LoggerManager implements LoggerInterface
{
    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);
    }

    public function debug($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->debug($message, $context);
    }

    public function request($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->info($message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->info($message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->notice($message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->warning($message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->error($message, $context);
    }

}
