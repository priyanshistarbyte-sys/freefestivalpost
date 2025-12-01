<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
 
    protected $fillable = [
        'photo_status_id',
        'photo',
        'created_at',
        'updated_at'
    ];

    public function status()
    {
        return $this->belongsTo(PhotoStatus::class, 'photo_status_id', 'id');
    }
}
