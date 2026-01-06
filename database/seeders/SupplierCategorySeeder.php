<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplierCategory;

class SupplierCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Construction Supplies',
                'project_type' => 'Construction'
            ],
            [
                'name' => 'Office Supplies and Equipment', 
                'project_type' => 'Office'
            ],
            [
                'name' => 'Medical Supplies',
                'project_type' => 'Health'
            ],
            [
                'name' => 'Laboratory Supplies',
                'project_type' => 'Laboratory'
            ],
            [
                'name' => 'Food Services',
                'project_type' => 'Food'
            ],
        ];

        //foreach ($categories as $category) {
        //    SupplierCategory::firstOrCreate(['name' => $category]);
        //}
        foreach ($categories as $categoryData) {
            SupplierCategory::firstOrCreate(
                ['name' => $categoryData['name']],  // ✅ Search by name
                $categoryData  // ✅ Create/update with full array
            );
        }
    }
}
