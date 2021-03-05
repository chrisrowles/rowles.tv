<?php

namespace Rowles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Rowles\Models\SubscriptionPackage
 *
 * @property int $id
 * @property int $subscription_id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $interval
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubscriptionPackage extends Model
{
    use HasFactory;
}
