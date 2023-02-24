<?php

declare (strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $type
 * @property int $is_delete
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected ?string $table = 'account';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'is_delete' => 'integer', 'updated_at' => 'datetime', 'created_at' => 'datetime'];
}