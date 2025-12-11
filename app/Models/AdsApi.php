<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsApi extends Model
{
    //
     protected $table = 'ads_api';

    protected $fillable = [
        'ads_title',
        'ads_id',
        'app_id',
        'ads_type',
        'created_at',
        'updated_at',
    ];
        


    public function adsApi()
    {
        return $this->hasMany(AdsApi::class, 'app_id', 'id');
    }
}
