<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Exception\BusinessException;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use RedisException;

/**
 * 服務抽象类
 */
abstract class AbstractService
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Redis
     */
    #[Inject]
    protected Redis $redis;

    /**
     * @throws RedisException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->logger = ApplicationContext::getContainer()->get(LoggerFactory::class)->get();
    }

    /**
     * 抛出Api接口异常
     * @param int $code
     * @return void
     */
    public function throwApiException(int $code): void
    {
        throw new BusinessException($code);
    }

    /**
     * @param LengthAwarePaginatorInterface $paginate
     * @param callable $func
     * @param array $extend
     * @return array
     */
    protected function toPage(LengthAwarePaginatorInterface $paginate, callable $func, $extend = []): array
    {
        $item = $func($paginate->items());
        return array_merge([
            'list' => $item,
            'per_page' => $paginate->perPage(),
            'current' => $paginate->currentPage(),
            'total' => $paginate->total(),
            'last_page' => $paginate->lastPage(),
            'has_page' => $paginate->hasMorePages(),
        ], $extend);
    }
}