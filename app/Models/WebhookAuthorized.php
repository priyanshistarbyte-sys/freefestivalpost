<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookAuthorized extends Model
{
    use HasFactory;

    protected $table = 'webhook_authorized';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'transaction_id',
        'amount',
        'mobile',
        'email'
    ];
}