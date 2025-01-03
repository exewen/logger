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
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->debug('', $context);
    }

    public function request($message, array $context = [])
    {
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->info('', $context);
    }

    public function info($message, array $context = [])
    {
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->info('', $context);
    }

    public function notice($message, array $context = [])
    {
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->notice('', $context);
    }

    public function warning($message, array $context = [])
    {
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->warning('', $context);
    }

    public function error($message, array $context = [])
    {
        $context = $this->formatContext($message, $context);
        $this->driver(null, __FUNCTION__)->error('', $context);
    }

    private function formatContext($message, array $context): array
    {
        $messageTemp = [
            'message' => $message
        ];
        return $messageTemp + $context;
    }

}
