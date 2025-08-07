<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['first_name' => 'Super', 'last_name' => 'Admin', 'middle_initial' => null, 'username' => 'superadmin', 'email' => 'superadmin@example.com', 'role' => 'Super_Admin'],
            ['first_name' => 'BAC', 'last_name' => 'Secretary', 'middle_initial' => null, 'username' => 'bacsec', 'email' => 'bacsec@example.com', 'role' => 'BAC_Secretary'],
            ['first_name' => 'Supplier', 'last_name' => 'User', 'middle_initial' => null, 'username' => 'supplier', 'email' => 'supplier@example.com', 'role' => 'Supplier'],
            ['first_name' => 'Purchaser', 'last_name' => 'User', 'middle_initial' => null, 'username' => 'purchaser', 'email' => 'purchaser@example.com', 'role' => 'Purchaser'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name'     => $data['first_name'],
                    'last_name'      => $data['last_name'],
                    'middle_initial' => $data['middle_initial'],
                    'username'       => $data['username'],
                    'password'       => bcrypt('password'),
                ]
            );

            $user->assignRole($data['role']); // Spatie role assignment
        }
    }
}
