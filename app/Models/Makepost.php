<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Makepost extends Model
{
    protected $table = 'makepost';
 
    protected $fillable = [
        'user_id',
        'tamp_id',
        'post',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function tamplet()
    {
        return $this->belongsTo(Tamplet::class, 'tamp_id', 'id');
    }
}
