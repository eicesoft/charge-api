<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Model\Account;
use App\Model\Model;
use App\Model\User;

/**
 * 账户服务
 */
class AccountService extends AbstractService
{
    const DEFAULT_TITLE = "默认";

    /**
     * 初始化账本
     * @param User $user
     * @return Account
     */
    public function initialization(User $user): Account
    {
        $account = new Account();

        $account->title = self::DEFAULT_TITLE;
        $account->user_id = $user->id;
        $account->type = 0;
        $account->is_delete = Model::NONE_DELETE;

        $account->save();

        $user->default_account_id = $account->id;
        $user->save();

        return $account;
    }
}