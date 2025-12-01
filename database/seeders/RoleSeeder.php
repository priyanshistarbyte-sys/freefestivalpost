<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = Role::firstOrCreate(['name' => 'Admin']);
        $sub_admin = Role::firstOrCreate(['name' => 'Sub Admin']);
        // $user      = Role::firstOrCreate(['name' => 'User']);


        $admin->syncPermissions([
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
            'plan-manage',
            'plan-create',
            'plan-edit',
            'plan-delete',
        ]);

        $sub_admin->syncPermissions([
           'category-manage',
           'category-create',
           'category-edit',
           'category-delete',
           'user-manage',
           'user-create',
           'user-edit',
           'user-delete',
        ]);
    }
}
