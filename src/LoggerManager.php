<?php
declare(strict_types=1);

namespace Exewen\Logger;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Logger\Exception\InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;

class LoggerManager
{
    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected array $channels = [];
    private ConfigInterface $config;
    protected array $levels = [
        'debug' => Monolog::DEBUG,
        'info' => Monolog::INFO,
        'notice' => Monolog::NOTICE,
        'warning' => Monolog::WARNING,
        'error' => Monolog::ERROR,
        'critical' => Monolog::CRITICAL,
        'alert' => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];
    protected string $dateFormat = 'Y-m-d H:i:s';

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * 设置驱动
     * @param $driver
     * @param $function
     * @return LoggerInterface
     */
    protected function driver($driver = null, $function = null): LoggerInterface
    {
        if (is_null($driver) && !is_null($function)) {
            $driver = $function;
        }
        return $this->get($driver ?? 'info');
    }

    /**
     * 获取channels或构建channels实例
     * @param $name
     * @return LoggerInterface
     */
    protected function get($name): LoggerInterface
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    /**
     * 构建
     * @param $channelName
     * @return LoggerInterface
     */
    protected function resolve($channelName): LoggerInterface
    {
        $channelConfig = $this->configurationFor($channelName);

        if (is_null($channelConfig)) {
            throw new InvalidArgumentException("Log [{$channelName}] is not defined.");
        }
        $driverMethod = 'create' . ucfirst($channelConfig['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->channels[$channelName] = $this->{$driverMethod}($channelConfig);
        }

        throw new InvalidArgumentException("Driver [{$channelConfig['driver']}] is not supported.");
    }

    /**
     * 获取当前日志配置
     * @param $channelName
     * @return array|mixed|null
     */
    protected function configurationFor(&$channelName)
    {
        $find = $this->config->get("logging.channels.{$channelName}");
        if (is_null($find)) {
            // 默认驱动替换
            $channelName = $this->config->get("logging.default");
        }
        return $this->config->get("logging.channels.{$channelName}");
    }


    /**
     * Create an instance of the daily file log driver.
     *
     * @param array $channelConfig
     * @return LoggerInterface
     */
    protected function createDailyDriver(array $channelConfig)
    {
        return new Monolog($this->getMonologName(), [
            $this->prepareHandler(new RotatingFileHandler(
                $channelConfig['path'],
                $channelConfig['days'] ?? 30,
                $this->level($channelConfig),
                $channelConfig['bubble'] ?? true,
                $channelConfig['permission'] ?? null,
                $channelConfig['locking'] ?? false
            ), $channelConfig),
        ]);
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $channelConfig
     * @return LoggerInterface
     */
    protected function createSingleDriver(array $channelConfig)
    {
        return new Monolog($this->getMonologName(), [
            $this->prepareHandler(
                new StreamHandler(
                    $channelConfig['path'],
                    $this->level($channelConfig),
                    $channelConfig['bubble'] ?? true,
                    $channelConfig['permission'] ?? null,
                    $channelConfig['locking'] ?? false
                ), $channelConfig
            ),
        ]);
    }

    protected function prepareHandler(HandlerInterface $handler, array $config = []): HandlerInterface
    {
        $isHandlerFormatter = false;

        if (Monolog::API === 1) {
            $isHandlerFormatter = true;
        } elseif (Monolog::API === 2 && $handler instanceof FormattableHandlerInterface) {
            $isHandlerFormatter = true;
        }

        if ($isHandlerFormatter) {
            $handler->setFormatter($this->formatter($config['formatter'] ?? []));
        }

        return $handler;
    }

    private function formatter(array $config): LineFormatter
    {
        return new LineFormatter(
            $config['constructor']['format'] ?? null,
            $config['constructor']['date_format'] ?? $this->dateFormat,
            false,
            true
        );
    }

    protected function level(array $config): int
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }
        throw new InvalidArgumentException('Invalid log level.');
    }

    /**
     * 日志内标识(logging.DEBUG)
     * @return string
     */
    private function getMonologName(): string
    {
        return $this->config->get('logging.monolog_name', 'local');
    }

}