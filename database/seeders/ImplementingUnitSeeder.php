<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ImplementingUnit;

class ImplementingUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'City Health Office',
            'City Planning and Development Office', 
            'City Engineering Office',
            'General Services Office'
        ];

        foreach ($departments as $department) {
            ImplementingUnit::firstOrCreate(
                ['name' => $department],
                ['description' => "Procurement unit for {$department}"]
            );
        }
    }
}
