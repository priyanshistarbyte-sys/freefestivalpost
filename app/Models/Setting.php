<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    
    protected $table = 'setting';

    protected $fillable = [
        'option_name',
        'value',
        'created_at',
        'updated_at'
    ];
}
