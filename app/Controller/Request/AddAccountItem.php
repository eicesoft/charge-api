<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

class AddAccountItem extends FormRequest
{
    const FIELD_ACCOUNT_ID = 'account_id';
    const FIELD_TITLE = 'title';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::FIELD_TITLE => 'required|string',
            self::FIELD_ACCOUNT_ID => 'integer',
        ];
    }

    public function attributes(): array
    {
        return [
            self::FIELD_TITLE => '账本分类标题',
            self::FIELD_ACCOUNT_ID => '账户ID',
        ];
    }
}