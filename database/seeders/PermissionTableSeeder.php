<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $permissions = [
            'dashboard-manage',
            'sub-admin-dashboard-manage',
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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}
