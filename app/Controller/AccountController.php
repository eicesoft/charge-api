<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller;

use App\Middleware\AuthMiddleware;
use App\Service\AccountService;
use App\Util\UserUtil;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 *
 */
#[Controller(prefix: "account")]
#[Middleware(AuthMiddleware::class)]
class AccountController extends AbstractController
{
    #[Inject]
    private AccountService $accountService;

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
}