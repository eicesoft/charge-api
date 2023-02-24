<?php

/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;
abstract class Model extends BaseModel
{
    public const NONE_DELETE = 0;
    public const DELETED = 1;
}