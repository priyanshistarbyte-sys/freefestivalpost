<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamplet extends Model
{
    protected $table = 'tamplet';
 
    protected $fillable = [
        'sub_category_id',
        'free_paid',
        'event_date',
        'event',
        'path',
        'has_mask',
        'mask',
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

    public function getPlanImgNameAttribute($value)
    {
        if (empty($value)) return [];
        // Handle old JSON-encoded data
        if (str_starts_with(trim($value), '[')) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) $decoded = json_decode($decoded, true);
            return is_array($decoded) ? $decoded : [];
        }
        return array_filter(explode(',', $value));
    }

    public function setPlanImgNameAttribute($value)
    {
        $this->attributes['planImgName'] = is_array($value) ? implode(',', $value) : $value;
    }

    public function category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
