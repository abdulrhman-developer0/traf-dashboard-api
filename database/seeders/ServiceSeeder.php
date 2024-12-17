<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_category_id' => 1,
                'service_provider_id' => 1,
                'name' => 'جلسة تنظيف بشرة',
                'duration' => 60,
                'description' => 'جلسة متخصصة لتنظيف البشرة بعمق باستخدام أفضل المنتجات.',
                'rating' => 4.5,
                'price_before' => 00.00,
                'price_after' => 200.00,
                'is_offer' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_category_id' => 2,
                'service_provider_id' => 2,
                'name' => 'جلسة مكياج سوارية',
                'duration' => 90,
                'description' => 'جلسة مكياج سوارية باستخدام أحدث التقنيات لإطلالة رائعة.',
                'rating' => 4.8,
                'price_before' => 00.00,
                'price_before' => 300.00,
                'is_offer' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_category_id' => 3,
                'service_provider_id' => 3,
                'name' => 'جلسة مساج',
                'duration' => 75,
                'description' => 'جلسة مساج تساعد على الاسترخاء وتحسين الدورة الدموية.',
                'rating' => 4.7,
                'price_before' => 00.00,
                'price_after' => 250.00,
                'is_offer' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_category_id' => 3,
                'service_provider_id' => 4,
                'name' => 'جلسة باديكير ومناكير ورسم حنة  ',
                'duration' => 75,
                'description' => 'جلسة مناكير وباديكير ورسم حنة باحدث الاشكال والطلاءات .',
                'rating' => 4.7,
                'price_before' => 00.00,
                'price_after' => 250.00,
                'is_offer' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    
        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    
        // // Creating additional services linked to service categories
        // foreach (ServiceCategory::get(['id'])->pluck('id') as $serviceCategoryId) {
        //     Service::factory(30)->create([
        //         'service_category_id' => $serviceCategoryId
        //     ]);
        // }
    }
    
}
