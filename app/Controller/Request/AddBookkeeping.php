<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

class AddBookkeeping extends FormRequest
{
    const FIELD_ACCOUNT_ITEM_ID = 'account_item_id';
    const FIELD_MONEY = 'money';
    const FIELD_NOTE = 'note';
    const FIELD_ACCOUNT_ID = 'account_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::FIELD_ACCOUNT_ITEM_ID => 'required | integer | exists:account_item,id',
            self::FIELD_MONEY => 'required | money',
            self::FIELD_NOTE => 'string',
            self::FIELD_ACCOUNT_ID => 'integer',
        ];
    }

    public function attributes(): array
    {
        return [
            self::FIELD_ACCOUNT_ITEM_ID => '记账分类编号',
            self::FIELD_MONEY => '金额',
            self::FIELD_NOTE => '备注',
            self::FIELD_ACCOUNT_ID => '账本编号',
        ];
    }
}