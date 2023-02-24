<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use RedisException;

/**
 * 服務抽象类
 */
abstract class AbstractService
{
    #[Inject]
    protected Redis $redis;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_JSON);
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
}