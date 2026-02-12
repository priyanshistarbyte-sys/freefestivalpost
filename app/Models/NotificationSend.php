<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSend extends Model
{
    protected $table = 'notification_send';

    protected $fillable = [
        'title',
        'message',
        'image',
        'url',
        'status',
        'page',
        'page_data',
        'created_at',
        'updated_at'
    ];
}
