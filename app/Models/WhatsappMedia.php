<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMedia extends Model
{
    protected $table = 'whatsapp_media';
    protected $fillable = [
        'image',
        'title',
        'created_at',
        'updated_at'
    ];

}
