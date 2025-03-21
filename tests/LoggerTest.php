<?php
declare(strict_types=1);

namespace ExewenTest\Logger;

use Exewen\Di\Context\ApplicationContext;
use Exewen\Logger\Contract\LoggerInterface;
use Exewen\Logger\LoggerProvider;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
//    private Container $app;
    private $app;

    public function __construct()
    {
        parent::__construct();
        !defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));

        $app      = ApplicationContext::getContainer();
        // 服务注册
        $app->setProviders([LoggerProvider::class]);
        $this->app = $app;
    }

    public function testLog()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->app->get(LoggerInterface::class);
        $logger->info("info日志");
        $logger->debug("debug日志");
        $logger->error("error日志");
        $this->assertTrue(true);
    }


}