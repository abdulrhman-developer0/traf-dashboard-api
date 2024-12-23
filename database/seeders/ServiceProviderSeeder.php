<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
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
                'job' => 'فنان مكياج',
                'phone' => '0501234567',
                'address' => 'الرياض، حي الروضة',
                'rating' => 4.9,
            ],
            [
                'user_id' => 2,
                'is_personal' => false,
                'tax_registeration_number' => '1122334455',
                'city_id' => 302,
                'job' => 'مصفف شعر',
                'phone' => '0509876543',
                'address' => 'جدة، حي الصفا',
                'rating' => 4.6,
            ],
            [
                'user_id' => 3,
                'is_personal' => true,
                'tax_registeration_number' => null,
                'city_id' => 303,
                'job' => 'فني أظافر',
                'phone' => '0561234321',
                'address' => 'الدمام، حي الشاطئ',
                'rating' => 4.7,
            ],
            [
                'user_id' => 4,
                'is_personal' => true,
                'tax_registeration_number' => null,
                'city_id' => 303,
                'job' => 'فني أظافر',
                'phone' => '0561234321',
                'address' => 'الدمام، حي الشاطئ',
                'rating' => 4.7,
            ],
        ];

        $users = User::whereAccountType('service-provider')->get();


        for ($i = 0; $i < $users->count(); $i += 1) {
            // Insert service provider and get the ID of the inserted record
            $providerId = DB::table('service_providers')->insertGetId([
                'user_id' => $users[$i]->id,
                'is_personal' => $serviceProviders[$i]['is_personal'],
                'tax_registeration_number' => $serviceProviders[$i]['tax_registeration_number'],
                // 'city_id' => $provider['city_id'],
                'job' => $serviceProviders[$i]['job'],
                'phone' => $serviceProviders[$i]['phone'],
                'address' => $serviceProviders[$i]['address'],
                'rating' => $serviceProviders[$i]['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $start_date = now();
            $end_date   =  now()->addDays(30)->endOfDay();
            $package = Package::get()->random();
            Subscription::create([
                'service_provider_id' => $providerId,
                'package_id'    => $package->id,
                'payment_status' => 'paid',
                'start_date' => $start_date,
                'end_Date'   => $end_date,
                'amount'     => $package->price,
                'transaction_reference' => 's1e1e1d1e1r'
            ]);
        }
    }
}
