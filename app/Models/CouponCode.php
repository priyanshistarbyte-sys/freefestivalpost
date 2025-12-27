<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    //
    protected $table = 'coupon_code';

    protected $fillable = [
        'title',
        'name',
        'code',
        'total_qty',
        'start_date',
        'end_date',
        'total_days',
        'total_count_user_apply',
        'note',
        'status',
        'created_at',
        'updated_at'
    ];

}
