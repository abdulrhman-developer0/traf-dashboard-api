<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $schedules = [
            [
                'partner_service_provider_id' => 1,
                'service_id' => 1,
                'date' => '2024-11-20',
                'time' => '10:00:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 2,
                'service_id' => 2,
                'date' => '2024-11-21',
                'time' => '12:00:00',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 1,
                'service_id' => 1,
                'date' => '2024-11-22',
                'time' => '15:00:00',
                'status' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 2,
                'service_id' => 1,
                'date' => '2024-11-23',
                'time' => '09:30:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 3,
                'service_id' => 1,
                'date' => '2024-11-24',
                'time' => '14:00:00',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 1,
                'service_id' => 2,
                'date' => '2024-11-25',
                'time' => '11:00:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 2,
                'service_id' => 3,
                'date' => '2024-11-26',
                'time' => '16:00:00',
                'status' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 3,
                'service_id' => 3,
                'date' => '2024-11-27',
                'time' => '08:00:00',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 2,
                'service_id' => 3,
                'date' => '2024-11-28',
                'time' => '17:30:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 3,
                'service_id' => 1,
                'date' => '2024-11-29',
                'time' => '13:00:00',
                'status' => 'off',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 1,
                'service_id' => 1,
                'date' => '2024-11-30',
                'time' => '09:00:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 2,
                'service_id' => 2,
                'date' => '2024-12-01',
                'time' => '18:00:00',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_service_provider_id' => 3,
                'service_id' => 3,
                'date' => '2024-12-02',
                'time' => '10:30:00',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($schedules as $schedule) {
            DB::table('service_schedules')->insert($schedule);
        }
    }
}
