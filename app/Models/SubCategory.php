<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
   

    protected $fillable = [
        'category_id',
        'parent_category',
        'is_parent',
        'is_child',
        'image',
        'event_date',
        'mtitle',
        'mslug',
        'status',
        'lable',
        'lablebg',
        'noti_banner',
        'mask',
        'noti_quote',
        'plan_auto',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public static function slug_string($string)
    {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}
