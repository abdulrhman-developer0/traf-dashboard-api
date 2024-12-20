<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'باقة الشهر الواحد',
                'price' => 15.00,
                'duration_in_days' => 30,
            ],
            [
                'name' => 'باقة 6 شهور',
                'price' => 75.00,
                'duration_in_days' => 180,
            ],
            [
                'name' => 'باقة 12 شهر',
                'price' => 150.00,
                'duration_in_days' => 365,
            ],
        ];

        Package::insert($packages);
    }
}
