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

namespace App\Exception\Handler;

use App\Exception\TokenException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        $error = [
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'data' => env('APP_ENV', 'prod') == 'prod' ? [] : $throwable->getTrace(),
        ];

        $http_code = 200;
        if ($throwable instanceof TokenException) {
            $http_code = 401;   //Token类错误
        }

        return $response->withHeader('Server', env('SERVER_NAME', 'HttpServer'))
            ->withStatus($http_code)
            ->withBody(new SwooleStream(json_encode($error)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
