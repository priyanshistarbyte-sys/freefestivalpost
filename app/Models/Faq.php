<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';

    protected $fillable = [
        'question',
        'answer',
        'status',
        'created_at',
        'updated_at'
    ];

}
