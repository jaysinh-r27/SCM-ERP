<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['id' => 1, 'name' => 'Play Group', 'type' => 'school', 'status' => 1],
            ['id' => 2, 'name' => 'Nursery', 'type' => 'school', 'status' => 1],
            ['id' => 3, 'name' => 'KG', 'type' => 'school', 'status' => 1],
            ['id' => 4, 'name' => '1st', 'type' => 'school', 'status' => 1],
            ['id' => 5, 'name' => '2nd', 'type' => 'school', 'status' => 1],
            ['id' => 6, 'name' => '3rd', 'type' => 'school', 'status' => 1],
            ['id' => 7, 'name' => '4th', 'type' => 'school', 'status' => 1],
            ['id' => 8, 'name' => '5th', 'type' => 'school', 'status' => 1],
            ['id' => 9, 'name' => '6th', 'type' => 'school', 'status' => 1],
            ['id' => 10, 'name' => '7th', 'type' => 'school', 'status' => 1],
            ['id' => 11, 'name' => '8th', 'type' => 'school', 'status' => 1],
            ['id' => 12, 'name' => '9th', 'type' => 'school', 'status' => 1],
            ['id' => 13, 'name' => '10th', 'type' => 'school', 'status' => 1],
            ['id' => 14, 'name' => '11th', 'type' => 'school', 'status' => 1],
            ['id' => 15, 'name' => '12th', 'type' => 'school', 'status' => 1],
            ['id' => 16, 'name' => 'B.C.A', 'type' => 'college', 'status' => 1],
            ['id' => 17, 'name' => 'B.Com', 'type' => 'college', 'status' => 1],
            ['id' => 18, 'name' => 'BBA', 'type' => 'college', 'status' => 1],
            ['id' => 19, 'name' => 'B.Sc', 'type' => 'college', 'status' => 1],
            ['id' => 20, 'name' => 'M.C.A', 'type' => 'college', 'status' => 1],
            ['id' => 21, 'name' => 'M.Com', 'type' => 'college', 'status' => 1],
            ['id' => 22, 'name' => 'MBA', 'type' => 'college', 'status' => 1],
            ['id' => 23, 'name' => 'M.Sc', 'type' => 'college', 'status' => 1],
        ];

        foreach ($classes as $class) {
            StudentClass::updateOrCreate(
                ['id' => $class['id']],
                [
                    'name' => $class['name'],
                    'type' => $class['type'],
                    'status' => $class['status'],
                ]
            );
        }
    }
}
