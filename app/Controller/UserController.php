<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\UserInfoRequest;
use App\Service\AccountItemService;
use App\Service\AccountService;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;

/**
 * 用户相关接口
 */
#[Controller(prefix: "user")]
class UserController extends AbstractController
{
    #[Inject]
    private UserService $userService;

    #[Inject]
    private AccountService $accountService;

    #[Inject]
    private AccountItemService $accountItemService;

    /**
     * 用户注册
     * @param UserInfoRequest $request
     * @return array
     */
    #[PostMapping(path: "registry")]
    public function registry(UserInfoRequest $request): array
    {
        $username = $request->input(UserInfoRequest::FIELD_USERNAME);
        $password = $request->input(UserInfoRequest::FIELD_PASSWORD);

        $user = $this->userService->registry($username, $password);
        $account = $this->accountService->initialization($user);   //初始化默认账本
        $this->accountItemService->initialization($account);   //初始化默认账本记录分类

        return $this->success($user);
    }

    /**
     * 用户验证登录
     * @param UserInfoRequest $request
     * @return array
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     * @throws ContainerExceptionInterface
     */
    #[PostMapping(path: "auth")]
    public function auth(UserInfoRequest $request): array
    {
        $username = $request->input(UserInfoRequest::FIELD_USERNAME);
        $password = $request->input(UserInfoRequest::FIELD_PASSWORD);

        $user = $this->userService->auth($username, $password);

        return $this->success($user);
    }

    #[GetMapping(path: "get")]
    public function get(): array
    {
        $user = $this->userService->get(1);
        return $this->success($user);
    }
}
