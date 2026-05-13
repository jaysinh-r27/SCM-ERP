<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_users = [
            [
                'role_id' => 1,
                'user_id' => 1,
            ]
        ];

        foreach ($role_users as $role_user) {
            RoleUser::updateOrCreate(
                ['role_id' => $role_user['role_id'], 'user_id' => $role_user['user_id']],
                [
                    'role_id' => $role_user['role_id'],
                    'user_id' => $role_user['user_id'],
                ]
            );
        }
    }
}
