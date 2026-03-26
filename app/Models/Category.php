<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $table = 'categories';

    protected $fillable = [
        'title',
        'sort',
        'sub',
        'icon',
        'thumb',
        'status',
        'is_show_on_home',
        'is_new',
        'created_at',
        'updated_at'
    ];

    
}
