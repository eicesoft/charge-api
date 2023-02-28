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
     * @return array
     */
    public function list(?string $mouth = null, int $account_id = 0, int $page = 1, int $page_size = 20): array
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

        $groups = Bookkeeping::query()
            ->selectRaw("account_item.type,sum(money) as Money")
            ->leftJoin('account_item', 'account_item.id', '=', 'bookkeeping.account_item_id')
            ->where('bookkeeping.date', $mouth)
            ->where('bookkeeping.account_id', $account_id)
            ->groupBy(['account_item.type'])
            ->get()
            ->groupBy('type');

        $this->logger->info(json_encode($groups));
        return $this->toPage($paginate, function ($items) use ($paginate) {
//            return collect($items)->map(function ($item) {
//                $item->append('day');
//                return $item;
//            });
            return $items;
        }, [
            'group' => $groups
        ]);
    }

    /**
     * 消费情况数据统计
     * @param int $type
     * @param int $mode
     * @param int $account_id
     * @return array
     */
    public function statistics(int $type, int $mode, int $account_id = 0): array
    {
        $user = UserUtil::user();

        if ($account_id == 0) {
            $account_id = $user['default_account_id'];
        }

        switch ($mode) {
            case 0:
            default:
                $startDate = strtotime("this week");
                break;
            case 1:
                $startDate = strtotime(date('Y-m-01', time()));
                break;
            case 2:
                $startDate = strtotime(date('Y-01-01', time()));//Y-01-01
                break;
        }

        $groups = Bookkeeping::query()
            ->selectRaw("account_item.title as title,sum(money) as money")
            ->leftJoin('account_item', 'account_item.id', '=', 'bookkeeping.account_item_id')
            ->where('bookkeeping.created_at', '>', $startDate)
            ->where('bookkeeping.account_id', $account_id)
            ->where('account_item.type', $type)
            ->groupBy(['account_item.title'])
            ->orderByDesc('money')
            ->get()
            ->makeHidden(['day']);

        $total = $groups->sum(function ($item) {
            return floatval($item->money);
        });

        $groups->each(function ($item) use ($total) {
            $item->rate = floatval(number_format(floatval($item->money) / $total * 100, 2));

            return $item;
        });

        return [
            'list' => $groups->toArray(),
            'total' => $total,
        ];
    }
}