<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller;

use App\Controller\Request\AddBookkeeping;
use App\Controller\Request\BookkeepingBill;
use App\Controller\Request\BookkeepingList;
use App\Controller\Request\BookkeepingStatistics;
use App\Middleware\AuthMiddleware;
use App\Service\BookkeepingService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: "bookkeeping")]
#[Middleware(AuthMiddleware::class)]
class BookkeepingController extends AbstractController
{
    #[Inject]
    private BookkeepingService $bookkeepingService;

    /**
     * 记账
     * @param AddBookkeeping $request
     * @return array
     */
    #[PostMapping(path: "store")]
    public function store(AddBookkeeping $request): array
    {
        $account_item_id = intval($request->input(AddBookkeeping::FIELD_ACCOUNT_ITEM_ID));
        $money = floatval($request->input(AddBookkeeping::FIELD_MONEY));
        $note = $request->input(AddBookkeeping::FIELD_NOTE, '');
        $account_id = intval($request->input(AddBookkeeping::FIELD_ACCOUNT_ID, 0));

        $bookkeeping = $this->bookkeepingService->add($account_item_id, $money, $note, $account_id);

        return $this->success($bookkeeping);
    }

    /**
     * @param BookkeepingList $request
     * @return array
     */
    #[GetMapping(path: "list")]
    public function list(BookkeepingList $request): array
    {
        $mouth = $request->input($request::FIELD_MOUTH);
        $account_id = intval($request->input($request::FIELD_ACCOUNT_ID, 0));
        $page = $request->input($request::FIELD_PAGE, $request::DEFAULT_PAGE);
        $page_size = $request->input($request::FIELD_PAGE_SIZE, $request::DEFAULT_PAGE_SIZE);

        $bookkeeping_list = $this->bookkeepingService->list($mouth, $account_id, $page, $page_size);

        return $this->success($bookkeeping_list);
    }

    /**
     * 获得数据统计
     * @param BookkeepingStatistics $request
     * @return array
     */
    #[GetMapping(path: "statistics")]
    public function statistics(BookkeepingStatistics $request): array
    {
        $type = intval($request->input($request::FIELD_TYPE));
        $mode = intval($request->input($request::FIELD_MODE));
        $account_id = intval($request->input($request::FIELD_ACCOUNT_ID, 0));
        $statistics = $this->bookkeepingService->statistics($type, $mode, $account_id);

        return $this->success($statistics);
    }

    /**
     * 获得年度账单
     * @param BookkeepingBill $request
     * @return array
     */
    #[GetMapping(path: "bill")]
    public function bill(BookkeepingBill $request): array
    {
        $year = $request->input($request::FIELD_YEAR);
        $account_id = intval($request->input($request::FIELD_ACCOUNT_ID, 0));
        $bills = $this->bookkeepingService->bill($year, $account_id);

        return $this->success($bills);
    }
}