<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);

namespace App\Controller;

use App\Middleware\AuthMiddleware;
use App\Util\UserUtil;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

#[Controller(prefix: "index")]
class IndexController extends AbstractController
{
    #[GetMapping(path: "index")]
    #[Middleware(AuthMiddleware::class)]
    public function index(): array
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return $this->success([
            'method' => $method,
            'user' => UserUtil::user(),
            'message' => "Hello {$user}.",
        ]);
    }
}
