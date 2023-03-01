<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Util;

class StringHelper
{
    /**
     * 创建 UUID
     * @return string
     */
    public static function uuid(): string
    {
        $chars = md5(uniqid(mt_rand() . '', true));
        return substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
    }

    /**
     *
     * @param mixed $number
     * @return string
     */
    public static function formatNumber(mixed $number): string
    {
        return number_format($number, 2, '.', '');
    }
}