<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dailog extends Model
{
    protected $table = 'dailog';

    protected $fillable = [
        'app_id',
        'title',
        'description',
        'button1',
        'button2',
        'link',
        'image',
        'appversion',
        'forcefully',
        'other_forcefully',
        'isDisplay',
        'other_isDisplay',
        'o_type',
        'o_link',
        'created_at',
        'updated_at',
    ];

    public function dailog_app()
    {
        return $this->hasMany(ApplicationAdd::class, 'app_id', 'id');
    }
}
