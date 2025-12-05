<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    protected $table = 'fonts';
 
    protected $fillable = [
        'font_name',
        'file_path',
        'created_at',
        'updated_at'
    ];

}
