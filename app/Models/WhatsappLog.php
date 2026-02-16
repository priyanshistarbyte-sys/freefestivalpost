<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_logs';
    protected $fillable = [
        'cam_id',
        'mobile',
        'tamp_name',
        'status',
        'msg_type',
        'response',
        'created_at',
        'updated_at'
    ];
}
