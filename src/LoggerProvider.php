<?php
declare(strict_types=1);

namespace Exewen\Logger;

use Exewen\Di\ServiceProvider;
use Exewen\Logger\Contract\LoggerInterface;

class LoggerProvider extends ServiceProvider
{

    public function register()
    {
        $this->container->singleton(LoggerInterface::class);
    }

}