<?php
/*
 * Copyright (c) 2023 to eIcesoft.
 * Git: github.com/eicesoft/charge
 * Author: kelezyb
 * Mail: eicesoft@gmail.com
 */

declare (strict_types=1);

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $token
 * @property int $default_account_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected ?string $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [];

    protected array $hidden = ['password'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}