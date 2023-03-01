<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

class BookkeepingBill extends FormRequest
{
    public const FIELD_YEAR = 'year';
    public const FIELD_ACCOUNT_ID = 'account_id';

    public function rules(): array
    {
        return [
            self::FIELD_YEAR => 'required | string',
            self::FIELD_ACCOUNT_ID => 'integer',
        ];
    }

    public function attributes(): array
    {
        return [
            self::FIELD_YEAR => '记账年份',
            self::FIELD_ACCOUNT_ID => '账本 ID',
        ];
    }
}