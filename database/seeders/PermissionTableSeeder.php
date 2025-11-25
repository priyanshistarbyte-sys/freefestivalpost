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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
