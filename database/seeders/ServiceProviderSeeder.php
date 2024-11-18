<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $serviceProviders = [
            [
                'user_id' => 1,
                'is_personal' => true,
                'tax_registeration_number' => '1234567890',
                'city_id' => 301,
                'years_of_experience' => 10,
                'phone' => '0501234567',
                'address' => 'الرياض، حي الروضة',
                'rating' => 4.9,
            ],
            [
                'user_id' => 2,
                'is_personal' => false,
                'tax_registeration_number' => '1122334455',
                'city_id' => 302,
                'years_of_experience' => 8,
                'phone' => '0509876543',
                'address' => 'جدة، حي الصفا',
                'rating' => 4.6,
            ],
            [
                'user_id' => 3,
                'is_personal' => true,
                'tax_registeration_number' => null,
                'city_id' => 303,
                'years_of_experience' => 12,
                'phone' => '0561234321',
                'address' => 'الدمام، حي الشاطئ',
                'rating' => 4.7,
            ],
        ];
        
        foreach ($serviceProviders as $provider) {
            // Insert service provider and get the ID of the inserted record
            $providerId = DB::table('service_providers')->insertGetId([
                'user_id' => $provider['user_id'],
                'is_personal' => $provider['is_personal'],
                'tax_registeration_number' => $provider['tax_registeration_number'],
                'city_id' => $provider['city_id'],
                'years_of_experience' => $provider['years_of_experience'],
                'phone' => $provider['phone'],
                'address' => $provider['address'],
                'rating' => $provider['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
            // Insert into service_provider_partners table with the correct provider ID
            DB::table('service_provider_partners')->insert([
                'partner_service_provider_id' => $providerId, 
                'service_provider_id'=>$providerId
                // Corrected field name
            ]);
}
}
}
