<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Util;

use Hyperf\Context\Context;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;

/**
 * 用户登录会话相关工具方法
 */
class UserUtil
{
    /** @var float|int 登录过期时间 */
    public const TOKEN_EXPIRE_TIME = 60 * 60 * 24 * 30;
    /** @var string Context user key */
    private const FIELD_USER = 'user';

    /**
     * 获得当前登录的用户信息
     * @return mixed
     */
    public static function user(): array
    {
        return Context::get(self::FIELD_USER);
    }

    /**
     * 设置当前会话用户信息
     * @param mixed $user
     * @return void
     */
    public static function setUser(array $user): void
    {
        Context::set(self::FIELD_USER, $user);
    }

    /**
     * 清除环境变量(每次会话需要调用删除)
     */
    public static function clear(): void
    {
        Context::destroy(self::FIELD_USER);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public static function setLoginInfo(string $token, array $data, $expire = self::TOKEN_EXPIRE_TIME): void
    {
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);

        $key = self::getKey($token);
        $redis->set($key, json_encode($data));
        $redis->expire($key, $expire);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public static function getLoginInfo(string $token): ?array
    {
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);

        $key = self::getKey($token);

        $result = $redis->get($key);
        if ($result) {
            $redis->expire($key, self::TOKEN_EXPIRE_TIME);
//            var_dump($result);
            return json_decode($result, true);
        } else {
            return null;
        }
    }

    /**
     * 清理登录用户信息
     * @param string $token
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public static function clearLoginToken(string $token): void
    {
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        $key = self::getKey($token);
        $redis->del([
            $key
        ]);
    }

    /**
     * @param string $token
     * @return string
     */
    private static function getKey(string $token): string
    {
        return 'TOKEN:' . $token;
    }
}