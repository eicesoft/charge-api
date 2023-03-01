<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Service;

use App\Model\Bookkeeping;
use App\Util\StringHelper;
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
        $bookkeeping->year = date('Y');
        $bookkeeping->month = date('m');
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
            $rate = floatval($item->money) / $total * 100;
            $item->rate = floatval(StringHelper::formatNumber($rate));

            return $item;
        });

        return [
            'list' => $groups->toArray(),
            'total' => $total,
        ];
    }

    /**
     * 获得年度账单
     * @param string $year
     * @param int $account_id
     * @return array
     */
    public function bill(string $year, int $account_id = 0): array
    {
        $user = UserUtil::user();

        if ($account_id == 0) {
            $account_id = $user['default_account_id'];
        }

        $bills = Bookkeeping::query()
            ->selectRaw("date,month,account_item.type as type, sum(money) as money")
            ->leftJoin('account_item', 'account_item.id', '=', 'bookkeeping.account_item_id')
            ->where('bookkeeping.year', $year)
            ->where('bookkeeping.account_id', $account_id)
            ->groupBy(['date', 'account_item.type'])
            ->orderByDesc('month')
            ->get()
            ->makeHidden(['day'])
            ->groupBy('month');

        $result = [];
        $total_expend = $total_income = 0;
        foreach ($bills as $month => $bill) {
            $s = collect($bill)->pluck('money', 'type')->toArray();
            foreach (AccountItemService::ACCOUNT_ITEM_TYPES as $type) {
                if (!isset($s[$type])) {
                    $s[$type] = "0.00";
                }
            }
            $surplus = $s[AccountItemService::ACCOUNT_ITEM_TYPE_INCOME] - $s[AccountItemService::ACCOUNT_ITEM_TYPE_EXPEND];
            $s['surplus'] = StringHelper::formatNumber($surplus);
            $total_income += floatval($s[AccountItemService::ACCOUNT_ITEM_TYPE_INCOME]);
            $total_expend += floatval($s[AccountItemService::ACCOUNT_ITEM_TYPE_EXPEND]);
            $result[$month] = $s;
        }

        return [
            'list' => $result,
            'total' => [
                AccountItemService::ACCOUNT_ITEM_TYPE_INCOME => StringHelper::formatNumber($total_income),
                AccountItemService::ACCOUNT_ITEM_TYPE_EXPEND => StringHelper::formatNumber($total_expend),
                'surplus' => StringHelper::formatNumber(floatval($total_income - $total_expend)),
            ]
        ];
    }
}