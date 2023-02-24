<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller;

/**
 * response trait
 */
trait Response
{
    /**
     * 成功响应
     * @param mixed $data
     * @param int $code
     * @param string $message
     * @return array
     */
    public function success(mixed $data, int $code = 200, string $message = ''): array
    {
        return [
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * 异常响应
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @return array
     */
    public function error(string $message, int $code = 400, mixed $data = []): array
    {
        return [
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
    }
}