<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customframe extends Model
{
    protected $table = 'customframe';

    protected $fillable = [
        'user_id',
        'frame_name',
        'image',
        'free_paid',
        'status',
        'data',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
