<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::where('role', 'Admin')->first();
        
        if ($admin) {
            $adminRole = Role::where('name', 'Admin')->where('guard_name', 'admin')->first();
            if ($adminRole) {
                $admin->assignRole($adminRole);
            }
        }

        $sub_admin = Admin::where('role', 'Sub Admin')->first();

         if ($sub_admin) {
            $subadminRole = Role::where('name', 'Sub Admin')->where('guard_name', 'admin')->first();
            if ($subadminRole) {
                $sub_admin->assignRole($subadminRole);
            }
        }
    }
}