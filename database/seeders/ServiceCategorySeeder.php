<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $servicesCategories = [
            ['name' => 'مكياج', 'is_active' => true],
            ['name' => 'تقليم أظافر', 'is_active' => true],
            ['name' => 'مساج', 'is_active' => true],
            ['name' => 'تنظيف بشرة', 'is_active' => true],
            ['name' => 'باديكير', 'is_active' => true],
            ['name' => 'مناكير', 'is_active' => true],
            ['name' => 'عناية بالشعر', 'is_active' => true],
        ];

        foreach ($servicesCategories as $service) {
            DB::table('service_categories')->insert([
                'name' => $service['name'],
                'is_active' => $service['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
