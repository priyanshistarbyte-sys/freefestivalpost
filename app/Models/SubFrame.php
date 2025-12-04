<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubFrame extends Model
{
    protected $table = 'sub_frames';

    protected $fillable = [
        'frame_id', 'image','status', 'created_at', 'updated_at'
    ];

    public function frame()
    {
        return $this->belongsTo(Frame::class, 'frame_id', 'id');
    }

}
