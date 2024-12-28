<?php

namespace Database\Seeders;

use App\Models\AdPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // default ads price.
        AdPrice::create([
            'price'  => 100
        ]);
    }
}
