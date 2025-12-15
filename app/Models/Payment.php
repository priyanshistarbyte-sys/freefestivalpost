<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

     protected $fillable = [
        'user_id',
        'amount',
        'date',
        'transactionid',
        'status',
        'packageid',
        'price',
        'month',
        'created_at',
        'ref_status',
        'refund_id',
        'refundDate',
        'userRole',
        'referral_code'
       
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'packageid');
    }
}
