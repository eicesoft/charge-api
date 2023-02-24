<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $title
 * @property int $type
 * @property int $account_id
 * @property int $user_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 */
class AccountItem extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'account_item';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'account_id' => 'integer',
        'user_id' => 'integer',
        'updated_at' => 'datetime',
        'created_at' => 'datetime'
    ];
}
