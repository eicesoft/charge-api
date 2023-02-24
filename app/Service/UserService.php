<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Constants\ErrorCode;
use App\Model\User;
use App\Util\StringHelper;
use App\Util\UserUtil;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;

class UserService extends AbstractService
{
    /**
     * @param string $username
     * @param string $password
     * @return User|null
     */
    public function registry(string $username, string $password): ?User
    {
        $c = User::query()->where('username', $username)->count();

        if ($c > 0) {
            $this->throwApiException(ErrorCode::REGISTRY_USER_EXISTS);
        }

        $user = new User();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $user->save();

        return $user;
    }

    /**
     * 验证登录
     * @param string $username
     * @param string $password
     * @return User|void|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public function auth(string $username, string $password)
    {
        /** @var ?User $user */
        $user = User::query()->where('username', $username)->first();

        if ($user) {
            if (!password_verify($password, $user->password)) {
                $this->throwApiException(ErrorCode::AUTH_PASSWORD_ERROR);
            }

            if (!empty($user->token)) {
                UserUtil::clearLoginToken($user->token);    //防止多次登录, 剔除之前的账户信息
            }

            $token = StringHelper::uuid();
            $user->token = $token;
            $user->save();

            UserUtil::setLoginInfo($token, $user->toArray());

            return $user;
        } else {
            $this->throwApiException(ErrorCode::AUTH_USERNAME_NOT_EXISTS);
        }
    }

    public function get(int $id): ?User
    {
        return User::query()->find($id);
    }
}