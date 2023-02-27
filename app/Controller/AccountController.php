<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller;

use App\Controller\Request\AccountItemRequest;
use App\Controller\Request\AddAccountItem;
use App\Middleware\AuthMiddleware;
use App\Service\AccountItemService;
use App\Service\AccountService;
use App\Util\UserUtil;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 *
 */
#[Controller(prefix: "account")]
#[Middleware(AuthMiddleware::class)]
class AccountController extends AbstractController
{
    #[Inject]
    private AccountService $accountService;

    #[Inject]
    private AccountItemService $accountItemService;

    #[GetMapping(path: "all")]
    public function all(): array
    {
        $user = UserUtil::user();
        $accounts = $this->accountService->all($user['id']);

        return $this->success($accounts);
    }

    #[GetMapping(path: "default")]
    public function default(): array
    {
        $user = UserUtil::user();
        $accounts = $this->accountService->default($user);

        return $this->success($accounts);
    }

    #[GetMapping(path: "items")]
    public function items(AccountItemRequest $request): array
    {
        $account_id = intval($request->input($request::FIELD_ACCOUNT_ID, 0));
        $user = UserUtil::user();
        $items = $this->accountService->items($user, $account_id);

        return $this->success($items);
    }

    #[PostMapping(path: "create_item")]
    public function createItem(AddAccountItem $request): array
    {
        $title = $request->input($request::FIELD_TITLE);
        $account_id = intval($request->input($request::FIELD_ACCOUNT_ID, 0));
        if ($account_id == 0) {
            $user = UserUtil::user();
            $account_id = $user['default_account_id'];
        }
        $account = $this->accountService->get($account_id);
        $account_item = $this->accountItemService->createAccountItem($account, $title, $account->type);

        return $this->success($account_item);
    }
}