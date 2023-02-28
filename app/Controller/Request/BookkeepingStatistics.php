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
class BookkeepingStatistics extends PageRequest
{
    public const FIELD_MODE = 'mode';
    public const FIELD_TYPE = 'type';
    public const FIELD_ACCOUNT_ID = 'account_id';

    public function rules(): array
    {
        $rules = parent::rules();
        return array([
            self::FIELD_MODE => 'integer',
            self::FIELD_TYPE => 'integer',
            self::FIELD_ACCOUNT_ID => 'integer',
        ], $rules);
    }

    public function attributes(): array
    {
        $rules = parent::attributes();
        return array([
            self::FIELD_TYPE => '入/出账类型',
            self::FIELD_MODE => '时间类型',
            self::FIELD_ACCOUNT_ID => '账本 ID',
        ], $rules);
    }
}