<?php

declare(strict_types=1);

namespace App\Model;


/**
 * @property int $id
 * @property int $type
 * @property float $limit
 * @property int $user_id
 * @property int $updated_at
 * @property int $created_at
 */
class Property extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'property';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'limit' => 'decimal:2',
        'type' => 'integer',
        'user_id' => 'integer',
    ];
}
