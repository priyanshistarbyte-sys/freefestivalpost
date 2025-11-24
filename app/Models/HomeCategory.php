<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCategory extends Model
{
    protected $table = 'home_categories';
 

    protected $fillable = [
        'sub_category_id',
        'title',
        'sequence',
        'status',
        'is_show_on_home',
        'is_new',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
