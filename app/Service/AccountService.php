<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Constants\ErrorCode;
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
        $account->created_at = time();
        $account->updated_at = time();

        $account->save();

        $user->default_account_id = $account->id;
        $user->save();

        return $account;
    }

    /**
     * 获得用户的全部账本
     * @param int $user_id
     * @return array
     */
    public function all(int $user_id): array
    {
        return Account::query()
            ->where('user_id', $user_id)
            ->get()
            ->toArray();
    }

    /**
     * 获得用户当前账本
     * @param array $user
     * @return array
     */
    public function default(array $user): array
    {
        $account = Account::query()
            ->find($user['default_account_id']);

        if ($account) {
            return $account->toArray();
        } else {
            $this->throwApiException(ErrorCode::DEFAULT_ACCOUNT_ERROR);
        }
    }
}