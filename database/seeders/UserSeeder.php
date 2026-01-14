<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SupplierCategory;
use App\Models\ImplementingUnit;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'middle_initial' => null,
                'email' => 'superadmin@example.com',
                'role' => 'Super_Admin'
            ],
            [
                'first_name' => 'BAC',
                'last_name' => 'Secretary',
                'middle_initial' => null,
                'email' => 'bacsec@example.com',
                'role' => 'BAC_Secretary'
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name'     => $data['first_name'],
                    'last_name'      => $data['last_name'],
                    'middle_initial' => $data['middle_initial'],
                    'password'       => bcrypt('password'),
                    'is_read'        => false, // 
                    'account_status' => 'verified',
                ]
            );

            $user->assignRole($data['role']);
        }

        // 🔥 NEW: 2 Purchasers per Implementing Unit
        $units = ImplementingUnit::all();

        foreach ($units as $index => $unit) {
            for ($i = 1; $i <= 2; $i++) {
                $firstName = $i === 1 ? 'Head' : 'Senior';
                $lastName = $i === 1 ? 'Department' : 'Manager';
                $email = strtolower(str_replace(' ', '', $unit->name)) . "-{$firstName}@bacolod.gov.ph";  // ✅ UNIQUE!
                
                $purchaser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'first_name'            => $firstName,
                        'last_name'             => $lastName,
                        'middle_initial'        => 'R.',
                        'password'              => bcrypt('password'),
                        'implementing_unit_id'  => $unit->id,
                        'is_read'               => false,
                        'contact_no'            => '0995' . str_pad(($index + 1) * 100 + $i, 7, '0', STR_PAD_LEFT),
                        'account_status'        => 'verified',
                    ]
                );

                $purchaser->assignRole('Purchaser');
            }
        }

        // 🔥 Three Suppliers per Supplier Category
        $categories = SupplierCategory::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                $supplierName = "{$category->name} {$i}";

                $supplier = User::firstOrCreate(
                    [
                        'email' => strtolower(str_replace(' ', '_', $category->name)) . "_{$i}@supplier.com",
                    ],
                    [
                        'first_name'            => $supplierName,
                        'last_name'             => null,
                        'middle_initial'        => null,
                        'password'              => bcrypt('password'),
                        'supplier_category_id'  => $category->id,
                        'is_read'               => false,
                        'contact_no'            => '0917' . str_pad($category->id * 10 + $i, 7, '0', STR_PAD_LEFT), 
                        'account_status' => 'verified',
                    ]
                );

                $supplier->assignRole('Supplier');
            }
        }
    }
}
