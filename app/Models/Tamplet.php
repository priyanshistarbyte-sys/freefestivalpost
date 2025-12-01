<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamplet extends Model
{
    protected $table = 'tamplet';
 
    protected $fillable = [
        'sub_category_id',
        'free_paid',
        'type',
        'event_date',
        'path',
        'font_type',
        'font_size',
        'font_color',
        'lable',
        'lablebg',
        'language',
        'planImgName',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
