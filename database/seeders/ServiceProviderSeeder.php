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
                'job_title' => 'فنان مكياج',
                'phone' => '0501234567',
                'address' => 'الرياض، حي الروضة',
                'rating' => 4.9,
            ],
            [
                'user_id' => 2,
                'is_personal' => false,
                'tax_registeration_number' => '1122334455',
                'city_id' => 302,
                'job_title' => 'مصفف شعر',
                'phone' => '0509876543',
                'address' => 'جدة، حي الصفا',
                'rating' => 4.6,
            ],
            [
                'user_id' => 3,
                'is_personal' => true,
                'tax_registeration_number' => null,
                'city_id' => 303,
                'job_title' => 'فني أظافر',
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
                'job_title' => $provider['job_title'],
                'phone' => $provider['phone'],
                'address' => $provider['address'],
                'rating' => $provider['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
