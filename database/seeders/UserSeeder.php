<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     [
        //         'name' => 'admin',
        //         'email' => 'admin@example.com',
        //         'password' => Hash::make('111111111'),
        //         'department' => 'admin',
        //         'status_id' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],

        // ]);

        $usersData = [];

        for ($i = 1; $i <= 20; $i++) {

            $statusId = rand(1, 2);
            $department = rand(1, 3);

            $usersData[] = [
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $department,
                'status_id' => $statusId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('users')->insert($usersData);
    }
}
