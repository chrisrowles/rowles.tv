<?php

namespace Rowles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Rowles\Models\SubscriptionPackage
 *
 * @property int $id
 * @property string $product
 * @property string $price
 * @property string $nickname
 * @property string $description
 * @property string $unit_amount
 * @property string $billing_interval
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|SubscriptionPackage newModelQuery()
 * @method static Builder|SubscriptionPackage newQuery()
 * @method static Builder|SubscriptionPackage query()
 * @method static Builder|SubscriptionPackage whereBillingInterval($value)
 * @method static Builder|SubscriptionPackage whereCreatedAt($value)
 * @method static Builder|SubscriptionPackage whereDescription($value)
 * @method static Builder|SubscriptionPackage whereId($value)
 * @method static Builder|SubscriptionPackage whereNickname($value)
 * @method static Builder|SubscriptionPackage wherePrice($value)
 * @method static Builder|SubscriptionPackage whereProduct($value)
 * @method static Builder|SubscriptionPackage whereUnitAmount($value)
 * @method static Builder|SubscriptionPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $fillable = ['nickname', 'description'];

    /**
     * @param $value
     * @return string
     */
    public function getUnitAmountAttribute($value): string
    {
        return number_format($value/100, 2);
    }
}
