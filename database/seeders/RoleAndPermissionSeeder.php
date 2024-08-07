<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $role_admin = Role::create(['name' => 'admin']);
        $role_doctor = Role::create(['name' => 'doctor']);
        $role_patient = Role::create(['name' => 'patient']);
        // $role_admin->syncPermissions($permission);

        $permissions_admin = [
            'create users',
            'read users',
            'update users',
            'delete users',
        ];

        foreach ($permissions_admin as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $role_admin->givePermissionTo($permission);
        }
    }
}
