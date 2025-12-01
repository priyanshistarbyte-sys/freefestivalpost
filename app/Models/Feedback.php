<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
  
    protected $table = 'feedback';
 
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
    
}
