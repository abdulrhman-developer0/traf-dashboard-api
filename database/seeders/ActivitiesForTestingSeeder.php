<?php

namespace Database\Seeders;

use App\Enums\ActivityActions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitiesForTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ( ActivityActions::cases() as $case ) {
            activities($case, 'Test title', 'test description');
        }
    }
}
