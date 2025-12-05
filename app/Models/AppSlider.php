<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSlider extends Model
{
    //
    protected $table = 'appslider';

    protected $fillable = [
        'title',
        'image',
        'mid',
        'sub',
        'url',
        'status',
        'sort',
        'festivalDate',
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];
}
