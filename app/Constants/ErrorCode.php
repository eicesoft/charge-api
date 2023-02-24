<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;


    /**
     * @Message("Token未设置")
     */
    public const AUTH_EMPTY = 501;
    /**
     * @Message("Token错误")
     */
    public const AUTH_USER_EMPTY = 501;

    /**
     * @Message("用户名已存在.")
     */
    public const REGISTRY_USER_EXISTS = 301;

    /**
     * @Message("用户名不存在.")
     */
    public const AUTH_USERNAME_NOT_EXISTS = 302;
    public const AUTH_PASSWORD_ERROR = 303;

    /**
     * @Message("默认账本丢失, 请联系管理员修复.")
     */
    public const DEFAULT_ACCOUNT_ERROR = 310;
}
