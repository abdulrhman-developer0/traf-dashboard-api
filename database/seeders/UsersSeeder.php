<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $dev_user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin32224')
        ]);
        $dev_user->assignRole('Admin');

        $dev_user = User::factory()->create([
            'name' => 'Abed Said',
            'email' => 'abed.allah.said@gmail.com',
            'password' => Hash::make('abedsaid')
        ]);
        $dev_user->assignRole('Admin');


    }
}
