<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookFailed extends Model
{
    protected $table = 'webhook_failed';

    protected $fillable = [
        'web_fail_id',
        'w_date',
        'w_event',
        'transaction_id',
        'w_amount',
        'w_email',
        'w_mobile',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'w_date' => 'date',
    ];

    
}
