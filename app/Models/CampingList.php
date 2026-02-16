<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampingList extends Model
{
    protected $table = 'camping_list';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'date',
        'retarget',
        'created_at'
    ];
}
