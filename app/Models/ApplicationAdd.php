<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationAdd extends Model
{
    protected $table = 'application_add';

    protected $fillable = [
        'app_name',
        'app_package_name',
        'admob_main_id',
        'fb_main_id',
        'status',
        'adclick',
        'mode',
        'created_at',
        'updated_at'
    ];
}
