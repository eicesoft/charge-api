<?php

declare(strict_types=1);

namespace App\Model;


use Hyperf\Database\Model\Relations\HasOne;

/**
 * @property int $id
 * @property float $money
 * @property string $note
 * @property int $account_item_id
 * @property int $account_id
 * @property int $user_id
 * @property int $property_id
 * @property string $date
 * @property string $year
 * @property string $month
 * @property int $updated_at
 * @property int $created_at
 */
class Bookkeeping extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'bookkeeping';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    protected array $appends = ['day'];

    public function account_item(): HasOne
    {
        return $this->hasOne(AccountItem::class, 'id', 'account_item_id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function getDayAttribute($prop): string
    {
        return date('Y-m-d', $this->created_at);
    }

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'money' => 'decimal:2',
        'account_item_id' => 'integer',
        'account_id' => 'integer',
        'user_id' => 'integer',
    ];
}
