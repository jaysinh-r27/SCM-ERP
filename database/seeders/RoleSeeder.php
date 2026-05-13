<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Student',
                'slug' => 'student',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Parent',
                'slug' => 'parent',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => null,
                'status' => 1,
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'description' => $role['description'],
                    'status' => $role['status'],
                ]
            );
        }
    }
}
