<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // $dev_user = User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('admin32224')
        // ]);
        // $dev_user->assignRole('Admin');

        // $dev_user = User::factory()->create([
        //     'name' => 'Abed Said',
        //     'email' => 'abed.allah.said@gmail.com',
        //     'password' => Hash::make('abedsaid')
        // ]);
        // $dev_user->assignRole('Admin');
        $users = [
            ['name' => 'أحمد علي', 'email' => 'ahmed.ali@example.com'],
            ['name' => 'سارة محمد', 'email' => 'sara.mohamed@example.com'],
            ['name' => 'خالد حسن', 'email' => 'khaled.hassan@example.com'],
            ['name' => 'منى محمود', 'email' => 'mona.mahmoud@example.com'],
            ['name' => 'يوسف إبراهيم', 'email' => 'youssef.ibrahim@example.com'],
            ['name' => 'هدى أحمد', 'email' => 'hoda.ahmed@example.com'],
            ['name' => 'علي سعيد', 'email' => 'ali.saeed@example.com'],
            ['name' => 'نورا سامي', 'email' => 'nora.samy@example.com'],
            ['name' => 'عمر فؤاد', 'email' => 'omar.fouad@example.com'],
            ['name' => 'ليلى سمير', 'email' => 'laila.samir@example.com'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
