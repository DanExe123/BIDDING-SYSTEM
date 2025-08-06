<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'Admin'],
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => 'Super_Admin'],
            ['name' => 'BAC Sec', 'email' => 'bacsec@example.com', 'role' => 'BAC_Sec'],
            ['name' => 'Supplier', 'email' => 'supplier@example.com', 'role' => 'Supplier'],
            ['name' => 'Purchaser', 'email' => 'purchaser@example.com', 'role' => 'Purchaser'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
