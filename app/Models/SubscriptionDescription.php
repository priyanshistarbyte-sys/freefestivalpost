<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionDescription extends Model
{
    protected $table = 'subscription_descriptions';

    protected $fillable = [
        'plan_id',
        'title',
        'sign',
        'created_at',
        'updated_at'
    ];

}
