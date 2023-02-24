<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * 用户注册请求
 */
class UserInfoRequest extends FormRequest
{
    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::FIELD_USERNAME => 'required | string',
            self::FIELD_PASSWORD => 'required | string',
        ];
    }

    public function attributes(): array
    {
        return [
            self::FIELD_USERNAME => '用户名',
            self::FIELD_PASSWORD => '密码',
        ];
    }
}