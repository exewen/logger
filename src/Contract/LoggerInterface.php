<?php
declare(strict_types=1);

namespace Exewen\Logger\Contract;

interface LoggerInterface
{
    public function error($message, array $context = array());

    public function warning($message, array $context = array());

    public function notice($message, array $context = array());

    public function info($message, array $context = array());

    public function debug($message, array $context = array());

    public function request($message, array $context = array());
}