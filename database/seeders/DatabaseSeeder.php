<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsGroupsSeeder::class);
        $this->call(UsersSeeder::class);

        // categories.
        $this->call(ServiceCategorySeeder::class);

        // default ad price
        $this->call(AdsSeeder::class);

        // production seeders
        $this->call(PackagesSeeder::class);

        // $this->call(ActivitiesForTestingSeeder::class);

        // $this->call(CitySeeder::class);
        // $this->call(ClientSeeder::class);
        // $this->call(ServiceProviderSeeder::class);
        // $this->call(WorkerSeeder::class);
        // $this->call(ServiceSeeder::class);
        // $this->call(ServiceScheduleSeeder::class);
        // $this->call(BookingSeeder::class);
        // $this->call(ReviewSeeder::class);

    }
}