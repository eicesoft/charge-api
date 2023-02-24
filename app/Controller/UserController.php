<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccountService;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

#[Controller(prefix: "user")]
class UserController extends AbstractController
{
    #[Inject]
    private UserService $userService;

    #[Inject]
    private AccountService $accountService;

    #[GetMapping(path: "registry")]
    public function registry(): array
    {

        return $this->error("错误拉");
    }

    #[GetMapping(path: "get")]
    public function get(): array
    {
        $user = $this->userService->get(1);
        return $this->success($user);
    }
}
