<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;

class UserService
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
            throw new BusinessException(ErrorCode::REGISTRY_USER_EXISTS);
        }

        $user = new User();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $user->save();

        return $user;
    }

    public function get(int $id): ?User
    {
        return User::query()->find($id);
    }
}