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
                'price'         => 15.00,
                'price_after'   => 15 * (1 - 5 / 100),
                'duration_in_days' => 30,
                "ads_discount"     => 5,
            ],
            [
                'name' => 'باقة 6 شهور',
                'price' => 75.00,
                'price_after'   => 75 * (1 - 10 / 100),
                'duration_in_days' => 180,
                "ads_discount"     => 10,
            ],
            [
                'name' => 'باقة 12 شهر',
                'price' => 150.00,
                'price_after'   => 150 * (1 - 15 / 100),
                'duration_in_days' => 365,
                "ads_discount"     => 15,
            ],
        ];

        Package::insert($packages);
    }
}
