<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $offers = [
            [
                'service_id' => 1, 
                'discount_percentage' => 15,
                'description' => 'خصم 15% على جميع خدمات المكياج في صالوننا، لا تفوت الفرصة!',
                'price_after' => 85.00, 
                'start_at' => now(),
                'end_at' => now()->addDays(10), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 2, 
                'discount_percentage' => 20,
                'description' => 'خصم 20% على جميع جلسات تدليك الجسم، استمتع بالراحة والاسترخاء.',
                'price_after' => 100.00, 
                'start_at' => now(),
                'end_at' => now()->addDays(7), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 3, 
                'discount_percentage' => 10,
                'description' => 'خصم 10% على تنظيف البشرة، للحصول على بشرة ناعمة ومتألقة.',
                'price_after' => 72.00, 
                'start_at' => now(),
                'end_at' => now()->addDays(14), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($offers as $offer) {
            DB::table('serivce_offers')->insert($offer);
        }
    }
}
