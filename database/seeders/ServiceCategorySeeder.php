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
            ['name' => 'رسم الحنة', 'image_path' => 'logos/Hana-Drawing.svg', 'is_active' => true],
          
            ['name' => 'مكياج', 'image_path' => 'logos/makeup.svg', 'is_active' => true],
            ['name' => 'باديكير ومناكير ', 'image_path' => 'logos/nails.svg', 'is_active' => true],
            ['name' => 'مساج', 'image_path' => 'logos/spa-svgrepo-com.svg', 'is_active' => true],
            ['name' => 'بشرة', 'image_path' => 'logos/skincare-icon.svg', 'is_active' => true],
            ['name' => 'عناية بالشعر', 'image_path' => 'logos/hear-care.svg', 'is_active' => true],
            ['name' => 'عروض', 'image_path' => 'logos/offers.svg', 'is_active' => true]
        ];

        foreach ($servicesCategories as $service) {
            DB::table('service_categories')->insert([
                'name' => $service['name'],
                'image_path' => $service['image_path'],
                'is_active' => $service['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
