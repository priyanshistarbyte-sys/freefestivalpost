<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $table = 'complain';

    protected $fillable = [
        'complain_id',
        'user_id',
        'subject',
        'message',
        'reply',
        'status',
        'remark',
        'created_at',
        'updated_at'
    ];

     public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }


}
