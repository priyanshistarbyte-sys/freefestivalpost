<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
     use HasFactory, Notifiable,HasRoles;
     

     protected $table = 'admin';

     protected $guard_name = 'web';

     protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'password',
        'name',
        'business_name',
        'photo',
        'mobile',
        'email',
        'password',
        'b_email',
        'b_mobile2',
        'b_website',
        'ispaid',
        'expdate',
        'planStatus',
        'gender',
        'role',
        'address',
        'status',
        'note',
        'last_login',
        'otp',
        'business_category_id',
        'free_post_count',
        'gst_firm_name',
        'gst_no',
        'owner_name',
        'owner_birth_date',
        'business_anniversary_date',
        'city',
        'state',
        'pincode',
        'lang',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
