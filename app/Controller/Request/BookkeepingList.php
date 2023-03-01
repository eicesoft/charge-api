<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

/**
 *
 */
class BookkeepingList extends PageRequest
{
    public const FIELD_MOUTH = 'mouth';
    public const FIELD_ACCOUNT_ID = 'account_id';

    public function rules(): array
    {
        $rules = parent::rules();
        return array([
            self::FIELD_MOUTH => 'required | string',
            self::FIELD_ACCOUNT_ID => 'integer',
        ], $rules);
    }

    public function attributes(): array
    {
        $rules = parent::attributes();
        return array([
            self::FIELD_MOUTH => '记账月份',
            self::FIELD_ACCOUNT_ID => '账本 ID',
        ], $rules);
    }
}