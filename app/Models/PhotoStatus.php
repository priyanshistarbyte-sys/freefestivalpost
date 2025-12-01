<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoStatus extends Model
{
    protected $table = 'photo_status';
 
    protected $fillable = [
        'title',
        'image',
        'lable',
        'lablebg',
        'created_at',
        'updated_at'
    ];
}
