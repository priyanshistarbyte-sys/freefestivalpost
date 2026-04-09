<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $table = 'frames';

    protected $fillable = [
        'frame_name','free_paid','status','image','data','logosection','sub_category_id','created_at','updated_at'
    ];
}
