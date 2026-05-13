<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::query()->where('status', 1)->pluck('id')->toArray();
        $roles = Role::query()->where('id', 1)->where('status', 1)->pluck('id')->toArray();

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                RolePermission::updateOrCreate(
                    ['role_id' => $role, 'permission_id' => $permission],
                    [
                        'role_id' => $role,
                        'permission_id' => $permission,
                    ]
                );
            }
        }
    }
}
