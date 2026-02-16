<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    //
    protected $table = 'whatsapp_template';
    protected $fillable = [
        'image',
        'tamp_name',
        'template',
        'type',
        'status',
        'media',
        'param',
        'lang',
        'note',
        'bulk_status',
        'sort',
        'created_at',
        'updated_at'
    ];
    
}
