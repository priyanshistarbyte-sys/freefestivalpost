<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';

    protected $fillable = [
        'plan_name',
        'special_title',
        'duration',
        'duration_type',
        'price',
        'discount_price',
        'discount',
        'status',
        'sequence',
        'created_at',
        'updated_at'
    ];

    public function descriptionsItem()
    {
        return $this->hasMany(SubscriptionDescription::class, 'plan_id', 'id');
    }
}
