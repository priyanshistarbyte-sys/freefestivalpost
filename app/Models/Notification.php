<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'user_id',
        'oprating_system',
        'app_version',
        'token',
        'device_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'app_version' => 'integer'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}