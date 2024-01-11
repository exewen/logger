<?php

declare(strict_types=1);

namespace Exewen\Logger;

use Exewen\Logger\Contract\LoggerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\NormalizerFormatter;

class ConfigRegister
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                LoggerInterface::class => Logger::class,
            ],

            'app' => [
                'app_env' => 'logging'
            ],

            'logging' => [
                'default' => 'info',
                // driver: daily/single
                'channels' => [
                    'info' => [
                        'driver' => 'daily',
                        'path' => BASE_PATH_PKG . '/logs/info.log',
                        'days' => 30,
                        'level' => 'debug',

                        'enable' => false,
                    ],
                    'error' => [
                        'driver' => 'daily',
                        'path' => BASE_PATH_PKG . '/logs/error.log',
                        'days' => 30,
                        'level' => 'error',
                    ],
                    'request' => [
                        'driver' => 'single',
                        'path' => BASE_PATH_PKG . '/logs/request.log',
                        'days' => 30,
                        'level' => 'debug',
                        'formatter' => [
                            'constructor' => [
                                'format' => LineFormatter::SIMPLE_FORMAT,
                                'dateFormat' => NormalizerFormatter::SIMPLE_DATE,
                            ],
                        ],
                    ],
                    'debug' => [
                        'driver' => 'single',
                        'path' => BASE_PATH_PKG . '/logs/debug.log',
                        'days' => 30,
                        'level' => 'debug',
                        'formatter' => [
                            'constructor' => [
                                'format' => LineFormatter::SIMPLE_FORMAT,
                                'dateFormat' => NormalizerFormatter::SIMPLE_DATE,
                            ],
                        ],
                    ]
                ]

            ],

        ];
    }
}
