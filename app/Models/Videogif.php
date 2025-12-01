<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videogif extends Model
{
    use HasFactory;

    protected $table = 'videogif';

    protected $fillable = [
        'type',
        'sub_category_id',
        'free_paid',
        'status',
        'lable',
        'lablebg',
        'path',
        'thumb'
    ];

   
    public function category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}