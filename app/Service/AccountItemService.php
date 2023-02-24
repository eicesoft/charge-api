<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Model\Account;
use App\Model\AccountItem;

/**
 * 账本记录分类
 */
class AccountItemService extends AbstractService
{
    /** @var int 支出 */
    public const ACCOUNT_ITEM_TYPE_EXPEND = 1;
    /** @var int 收入 */
    public const ACCOUNT_ITEM_TYPE_INCOME = 2;

    /** @var string[] */
    private const DEFAULT_EXPEND_ACCOUNT_ITEMS = [
        '交通',
        '通讯',
        '饮食',
        '娱乐',
        '育儿',
        '学习'
    ];

    private const DEFAULT_INCOME_ACCOUNT_ITEMS = [
        '工资',
        '奖金',
        '红包',
        '股票',
    ];

    /**
     * 初始化账号记录分类
     * @param Account $account
     * @return void
     */
    public function initialization(Account $account): void
    {
        $this->createAccountItems($account, self::DEFAULT_EXPEND_ACCOUNT_ITEMS, self::ACCOUNT_ITEM_TYPE_EXPEND);
        $this->createAccountItems($account, self::DEFAULT_INCOME_ACCOUNT_ITEMS, self::ACCOUNT_ITEM_TYPE_INCOME);
    }

    /**
     * 批量创建账户记录分类
     * @param Account $account
     * @param $titles
     * @param $type
     * @return void
     */
    private function createAccountItems(Account $account, $titles, $type): void
    {
        foreach ($titles as $title) {
            $_ = $this->createAccountItem($account, $title, $type);
        }
    }

    /**
     * 创建账户记录分类
     * @param Account $account
     * @param string $title
     * @param int $type
     * @return AccountItem
     */
    private function createAccountItem(Account $account, string $title, int $type): AccountItem
    {
        $account_item = new AccountItem();
        $account_item->title = $title;
        $account_item->type = $type;
        $account_item->account_id = $account->id;
        $account_item->user_id = $account->user_id;
        $account_item->created_at = time();
        $account_item->updated_at = time();

        $account_item->save();

        return $account_item;
    }
}