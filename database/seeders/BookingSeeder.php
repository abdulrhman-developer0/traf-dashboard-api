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

        foreach ($serviceIds as $serviceId) {
            foreach ($clientIds as $clientId) {
                Booking::create([
                    'client_id' => $clientId,
                    'service_id' => $serviceId,
                    'date'       => now()->format('m/d/Y'),
                    'status'     => 'confirmed',
                ]);
            }
        }
    }
}
