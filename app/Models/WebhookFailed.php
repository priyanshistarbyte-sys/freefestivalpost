<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookFailed extends Model
{
    protected $table = 'webhook_failed';

    protected $fillable = [
        'date',
        'event',
        'transaction_id',
        'amount',
        'email',
        'mobile',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    
}
