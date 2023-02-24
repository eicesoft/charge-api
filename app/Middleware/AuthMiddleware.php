<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Util\UserUtil;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RedisException;

/**
 * 用户验证中间件
 */
class AuthMiddleware implements MiddlewareInterface
{
    const AUTHORIZATION = 'Authorization';

    /**
     * @var ContainerInterface
     */
    #[Inject]
    protected ContainerInterface $container;

    protected LoggerInterface $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeaderLine(self::AUTHORIZATION);

        if (!$authorization) {
            throw new BusinessException(ErrorCode::AUTH_EMPTY);
        }

        $user = UserUtil::getLoginInfo($authorization);
        $this->logger->info($authorization);
//        $this->logger->info($user);
        if (!$user) {
            throw new BusinessException(ErrorCode::AUTH_USER_EMPTY);
        }

        UserUtil::setUser($user);
        $response = $handler->handle($request);
        UserUtil::clear();

        return $response;
    }
}