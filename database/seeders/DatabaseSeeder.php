<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PermissionsGroupsSeeder::class);
        $this->call(UsersSeeder::class);

        $this->call(CitySeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ServiceProviderSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        // $this->call(ServiceSeeder::class);
        // $this->call(ServiceScheduleSeeder::class);
        $this->call(ReviewSeeder::class);
        // $this->call(OfferSeeder::class);
        
    }
}
