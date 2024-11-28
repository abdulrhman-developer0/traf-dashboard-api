<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personalIds =  ServiceProvider::where('is_personal', 1)->pluck('id');

        $clientIds   = Client::pluck('id');

        $serviceIds  = Service::whereIn('service_provider_id', $personalIds->toArray())->pluck('id');

        for ($i = 0; $i < 20; $i += 1) {
            Booking::create([
                'client_id'    => $clientIds->random(),
                'service_id'   => $serviceIds->random(),
                'reference_id' => $personalIds->random(),
                "date"         => fake()->date('m/d/Y H:i', now()->addDays(90)),
                'status'        => fake()->randomElement(['pending', 'canceled', 'done']),
            ]);
        }
    }
}
