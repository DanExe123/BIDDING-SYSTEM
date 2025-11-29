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
            'Construction Supplies',
            'Office Supplies and Equipment',
            'Medical Supplies',
            'Laboratory Supplies',
            'Food Services',
        ];

        foreach ($categories as $category) {
            SupplierCategory::firstOrCreate(['name' => $category]);
        }
    }
}
