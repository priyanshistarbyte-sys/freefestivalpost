<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $sub_admin = Role::firstOrCreate(['name' => 'Sub Admin', 'guard_name' => 'admin']);
        


        $admin->syncPermissions([
            'dashboard-manage',
            'role-manage',
            'role-create',
            'role-edit',
            'role-delete',
            'category-manage',
            'category-create',
            'category-edit',
            'category-delete',
            'user-manage',
            'user-create',
            'user-edit',
            'user-delete',
            'user-transaction',
            'frame-manage',
            'sub-frame-manage',
            'setting-manage',
            'font-manage',
            'send-notification-manage',
            'slider-manage',
            'faq-manage',
            'plan-manage',
            'plan-create',
            'plan-edit',
            'plan-delete',
            'admin-user-manage',
            'report-manage',
            'coupon-manage',
            'coupon-create',
            'coupon-edit',
            'coupon-delete',
        ]);

        $sub_admin->syncPermissions([
           'dashboard-manage',
           'sub-admin-dashboard-manage',
           'category-manage',
           'category-create',
           'category-edit',
           'category-delete',
           'user-manage',
           'user-create',
           'user-edit',
           'user-delete',
           'slider-manage',
           'faq-manage',
        ]);
    }
}
