<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

namespace App\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

class PageRequest extends FormRequest
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_PAGE_SIZE = 20;

    public const FIELD_PAGE = 'page';
    public const FIELD_PAGE_SIZE = 'page_size';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::FIELD_PAGE => 'integer',
            self::FIELD_PAGE_SIZE => 'integer',
        ];
    }

    public function attributes(): array
    {
        return [
            self::FIELD_PAGE => '页码',
            self::FIELD_PAGE_SIZE => '每页数量',
        ];
    }
}