<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'handlers' => [
            [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level' => Monolog\Logger::INFO,
                ],
            ],
            [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => 'php://output',
                    'level' => Monolog\Logger::INFO,
                ],
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,

            'constructor' => [
                'format' => "[%datetime%] %channel%.%level_name%: %message% %context%%\n",
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
