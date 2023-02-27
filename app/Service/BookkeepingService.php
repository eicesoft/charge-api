<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Model\Bookkeeping;
use App\Util\UserUtil;

class BookkeepingService extends AbstractService
{
    /**
     * 添加记账
     * @param int $account_item_id
     * @param float $money
     * @param string $note
     * @param int $account_id
     * @return Bookkeeping
     */
    public function add(int $account_item_id, float $money, string $note = '', int $account_id = 0): Bookkeeping
    {
        $user = UserUtil::user();

        if ($account_id == 0) {
            $account_id = $user['default_account_id'];
        }

        $now = time();
        $bookkeeping = new Bookkeeping();

        $bookkeeping->money = $money;
        $bookkeeping->note = $note;
        $bookkeeping->account_item_id = $account_item_id;
        $bookkeeping->account_id = $account_id;
        $bookkeeping->user_id = $user['id'];
        $bookkeeping->date = date('Y-m');
        $bookkeeping->created_at = $now;
        $bookkeeping->updated_at = $now;

        $bookkeeping->save();

        return $bookkeeping;
    }

    /**
     * 获取每日记账列表
     * @param string|null $mouth
     * @param int $account_id
     * @param int $page
     * @param int $page_size
     * @return mixed
     */
    public function list(?string $mouth = null, int $account_id = 0, int $page = 1, int $page_size = 20): mixed
    {
        if (empty($mouth)) {
            $mouth = date('Y-m');   //默认当前月份
        }
        $user = UserUtil::user();

        if ($account_id == 0) {
            $account_id = $user['default_account_id'];
        }

        $paginate = Bookkeeping::query()
            ->with([
                'account_item' => function ($with_query) {
                    $with_query->select(['id', 'title', 'type']);
                }])
            ->where('date', $mouth)
            ->where('account_id', $account_id)
            ->orderByDesc('created_at')
            ->paginate($page_size, ['*'], 'page', $page);

        $this->logger->info(json_encode($paginate));
        return $this->toPage($paginate, function ($items) use ($paginate) {
//            return collect($paginate->items())->groupBy('day');
            return $items;
        });
    }
}