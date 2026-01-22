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
        'created_at',
        'updated_at'
    ];
}
